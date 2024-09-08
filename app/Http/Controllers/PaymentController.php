<?php

namespace App\Http\Controllers;

use App\Actions\CreateSuscriptionAction;
use App\Actions\CreateUserAction;
use App\Constants\InvoicesStatus;
use App\Factories\PaymentFactory;
use App\Http\Requests\PaymentCreateRequest;
use App\Http\Requests\PaymentInvoiceSearchRequest;
use App\Models\BuyerIdType;
use App\Models\Currency;
use App\Models\Invoice;
use App\Models\Microsite;
use App\Models\OptionalField;
use App\Models\Payment;
use App\Models\SuscriptionPlan;
use App\Services\PaymentGatewayService;
use App\Strategies\Microsites\DonationMicrositeStrategy;
use App\Strategies\Microsites\InvoiceMicrositeStrategy;
use App\Strategies\Microsites\MicrositeContext;
use App\Strategies\Microsites\SubscriptionMicrositeStrategy;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Response;

class PaymentController extends Controller
{
    /**
     * @throws \Exception
     */
    public function create($id): Response
    {
        $currencies = Currency::all();
        $microsite = Microsite::with(['typeSite', 'category', 'currencies'])->findOrFail($id);
        $buyer_id_types = BuyerIdType::all();
        $optionals = OptionalField::all();
        $plans = SuscriptionPlan::where('microsite_id', $microsite->id)->get();

        $context = new MicrositeContext;

        switch ($microsite->typeSite->name) {
            case 'Donation':
                $context->setStrategy(new DonationMicrositeStrategy);
                break;
            case 'Invoice':
                $context->setStrategy(new InvoiceMicrositeStrategy);
                break;
            case 'Subscription':
                $context->setStrategy(new SubscriptionMicrositeStrategy);
                break;
            default:
                throw new \Exception('Unknown microsite type');
        }

        $component = $context->renderComponent($microsite);

        return inertia($component, [
            'microsite' => $microsite,
            'currencies' => $currencies,
            'buyer_id_types' => $buyer_id_types,
            'optionals' => $optionals,
            'plans' => $plans,
        ]);
    }

    public function processPayment(PaymentCreateRequest $request): JsonResponse
    {
        $gatewayType = $request->input('paymentMethod');

        $currencyCode = Currency::where('id', $request->currency)->pluck('code')->first();

        $userIp = $request->ip();
        $userAgent = $request->header('User-Agent');
        $data = $request->validated();
        $data['userIp'] = $userIp;
        $data['userAgent'] = $userAgent;
        $data['currencyCode'] = $currencyCode;

        Log::info('Data raw request:', $data);

        try {
            $gateway = PaymentFactory::create($gatewayType, $data);
            $paymentService = new PaymentGatewayService($gateway, $data);

            $response = $paymentService->processPayment();

            return match (true) {
                isset($response['processUrl']) => response()->json(['redirect_url' => $response['processUrl']]),
                isset($response['status']) && $response['status']['status'] === 'FAILED' => response()->json(['error' => $response['status']['message']], 400),
                default => response()->json(['message' => 'Respuesta procesada correctamente', 'data' => $response]),
            };

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function handleReturn($paymentId): RedirectResponse|JsonResponse
    {
        $payment = Payment::where('return_id', $paymentId)->firstOrFail();
        $paymentMethod = $payment->payment_method;
        $paymentArray = $payment->toArray();

        try {
            $gateway = PaymentFactory::create($paymentMethod, $paymentArray);
            $paymentService = new PaymentGatewayService($gateway, $paymentArray);
            $sessionData = $paymentService->QueryPayment();

            $isInvoicePayment = false;
            if (isset($sessionData['request']['fields'])) {
                foreach ($sessionData['request']['fields'] as $field) {
                    if (isset($field['keyword']) && $field['keyword'] === 'order_number') {
                        $isInvoicePayment = true;
                        $orderNumber = $field['value'];
                        break;
                    }
                }
            }

            $payment->status = $sessionData['status']['status'];

            if (isset($sessionData['request']['payment'])) {
                $payment->reference = $sessionData['request']['payment']['reference'];
            } elseif (isset($sessionData['request']['subscription'])) {
                $payment->reference = $sessionData['request']['subscription']['reference'];
            }

            $payment->date = $sessionData['status']['date'];
            $payment->ip_address = $sessionData['request']['ipAddress'];
            $payment->user_agent = $sessionData['request']['userAgent'];

            if ($sessionData['status']['status'] == 'APPROVED') {
                if (! isset($sessionData['request']['subscription'])) {
                    $payment->cus_code = $sessionData['payment'][0]['authorization'];
                    if ($isInvoicePayment) {
                        $invoice = Invoice::where('order_number', $orderNumber)->first();
                        Log::info('Invoice Query:', $invoice->toArray());
                        $invoice->status = InvoicesStatus::paid;
                        $invoice->payment_id = $payment->id;
                        $invoice->save();
                    }
                } elseif (isset($sessionData['request']['subscription'])) {
                    $sessionData['request']['payer']['password'] = '123456';
                    $sessionData['request']['payer']['rol'] = 3;
                    $sessionData['plan'] = $payment->plan;
                    $userSuscriptor = CreateUserAction::execute($sessionData['request']['payer']);
                    $sessionData['user'] = $userSuscriptor;
                    $sessionData['payment_id'] = $payment->id;
                    Log::info('SuscriptionData:', $sessionData);
                    CreateSuscriptionAction::execute($sessionData);
                }

            }
            if (isset($sessionData['request']['payer'])) {
                $payerData = [
                    'document' => $sessionData['request']['payer']['document'],
                    'document_type' => $sessionData['request']['payer']['documentType'],
                    'name' => $sessionData['request']['payer']['name'],
                    'surname' => $sessionData['request']['payer']['surname'],
                    'email' => $sessionData['request']['payer']['email'],
                    'mobile' => $sessionData['request']['payer']['mobile'],
                ];

                $payerJson = json_encode($payerData);

                $payment->payer = $payerJson;
            }

            $payment->save();

            return redirect()->route('payment.show', ['payment' => $payment])
                ->with('success', 'Payment processed successfully');
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }

    public function show($id): Response
    {
        $payment = Payment::with(['microsite', 'currency'])->findOrFail($id);

        return inertia('Payments/ShowPayment', ['payment' => $payment]);
    }

    public function paymentDetail($id): Response
    {
        $payment = Payment::with(['microsite', 'currency'])->findOrFail($id);

        return inertia('Payments/DetailsPayment', ['payment' => $payment]);
    }

    public function invoiceSearch(PaymentInvoiceSearchRequest $request): Response|JsonResponse
    {
        $order = $request->validated();
        Log::info('Order data:', $order);
        $invoice = Invoice::where('order_number', $order)
            ->with(['microsite', 'buyeridtype', 'currency', 'user'])
            ->first();
        Log::info('Order invoice:', $invoice ? $invoice->toArray() : []);

        if (! $invoice) {
            return response()->json([
                'error' => 'No tiene facturas pendientes por pagar.',
            ], 400);
        }

        if ($invoice->status !== InvoicesStatus::paid) {
            return response()->json([
                'redirect' => route('payment.invoice.index', ['invoice' => $invoice]),
                'invoice' => $invoice->toArray(),
            ]);
        }

        return response()->json([
            'error' => 'La factura ya ha sido pagada.',
        ], 400);
    }

    public function invoiceIndex(Invoice $invoice)
    {
        $invoice->load(['microsite', 'buyeridtype', 'currency', 'user']);

        return inertia('Payments/SelectedInvoicePayment', [
            'invoice' => $invoice,
        ]);
    }
}
