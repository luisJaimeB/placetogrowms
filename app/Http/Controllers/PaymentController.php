<?php

namespace App\Http\Controllers;

use App\Factories\PaymentFactory;
use App\Http\Requests\PaymentCreateRequest;
use App\Models\BuyerIdType;
use App\Models\Currency;
use App\Models\Microsite;
use App\Models\OptionalField;
use App\Models\Payment;
use App\Services\PaymentGatewayService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Response;

class PaymentController extends Controller
{
    public function create($id): Response
    {
        $currencies = Currency::all();
        $microsite = Microsite::with(['typeSite', 'category', 'currencies'])->findOrFail($id);
        $buyer_id_types = BuyerIdType::all();
        $optionals = OptionalField::all();

        return inertia('Payments/CreatePayment', [
            'microsite' => $microsite,
            'currencies' => $currencies,
            'buyer_id_types' => $buyer_id_types,
            'optionals' => $optionals,
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

    public function handleReturn($paymentId)
    {
        $payment = Payment::where('return_id', $paymentId)->firstOrFail();
        $paymentMethod = $payment->payment_method;
        $paymentArray = $payment->toArray();

        try {
            $gateway = PaymentFactory::create($paymentMethod, $paymentArray);
            $paymentService = new PaymentGatewayService($gateway, $paymentArray);
            $sessionData = $paymentService->QueryPayment();

            $payment->status = $sessionData['status']['status'];
            $payment->reference = $sessionData['request']['payment']['reference'];
            $payment->date = $sessionData['status']['date'];
            $payment->ip_address = $sessionData['request']['ipAddress'];
            $payment->user_agent = $sessionData['request']['userAgent'];

            if ($sessionData['status']['status'] == 'APPROVED') {
                $payment->cus_code = $sessionData['payment'][0]['authorization'];
            }
            if (isset($sessionData['request']['payer'])) {
                $payerData = [
                    'document' => $sessionData['request']['payer']['document'],
                    'document_type' => $sessionData['request']['payer']['documentType'],
                    'name' => $sessionData['request']['payer']['name'],
                    'surname' => $sessionData['request']['payer']['surname'],
                    'email' => $sessionData['request']['payer']['email'],
                    'mobile' => $sessionData['request']['payer']['mobile']
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
}
