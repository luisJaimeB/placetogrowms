<?php

namespace App\Jobs;

use App\Actions\CreatePaymentAction;
use App\Constants\Periodicities;
use App\Factories\PaymentFactory;
use App\Models\Suscription;
use App\Services\PaymentGatewayService;
use Carbon\Carbon;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CollectSubscription implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    protected Suscription $subscription;

    public function __construct(Suscription $subscription)
    {
        $this->subscription = $subscription;
    }

    public function handle(): void
    {
        $subscription = $this->subscription;
            try {
                $response = $this->charge($subscription);
                $response['type'] = $subscription->microsite->type_site_id;
                $response['currency'] = $subscription->microsite->currencies()->first()->id ?? null;
                $response['buyer'] = $subscription->initialPayment->buyer;
                $response['micrositeId'] = $subscription->microsite->id;
                $response['paymentMethod'] = $subscription->initialPayment->payment_method;
                $response['plan'] = $subscription->suscriptionPlan->id;
                $response['suscriptionId'] = $subscription->id;

                if (isset($response['status']['status']) && in_array($response['status']['status'], ['APPROVED', 'REJECTED', 'PENDING'])) {

                    $payment = CreatePaymentAction::execute($response);
                    Log::info('Payment successfully processed for payment ID: ' . $payment->id);

                } else {
                    Log::info('Payment not processed for payment');
                }

                if (isset($response['status']['status']) && $response['status']['status'] === 'APPROVED') {
                    $nextBillingDate = $this->calculateNextBillingDate($subscription);
                    $subscription->next_billing_date = $nextBillingDate;
                    $subscription->save();
                    Log::info('Payment successfully processed for subscription ID: ' . $subscription->id);
                } elseif (isset($response['status']['status']) && $response['status']['status'] === 'REJECTED'){
                    $attempts = $subscription->suscriptionPlan->attempts;
                    $lapse = $subscription->suscriptionPlan->lapse;

                    Log::info('since the payment was rejected, it will be reattempted in the following hours: ' . $lapse);
                    for ($i = 0; $i < $attempts; $i++) {
                        sleep($lapse * 3600);
                        $retryResponse = $this->charge($subscription);

                        if (isset($retryResponse['status']['status']) && $retryResponse['status']['status'] === 'APPROVED') {
                            $nextBillingDate = $this->calculateNextBillingDate($subscription);
                            $subscription->next_billing_date = $nextBillingDate;
                            $subscription->save();
                            Log::info('Payment successfully processed for subscription ID: ' . $subscription->id . ' on retry attempt ' . ($i + 1));
                            break;
                        } elseif ($i === $attempts - 1) {
                            Log::info('Subscription ID: ' . $subscription->id . ' has been cancelled after ' . $attempts . ' failed attempts.');
                        }
                    }
                }
                else {
                    $statusMessage = $response['status']['message'] ?? 'No message provided';
                    Log::error('Payment failed for subscription ID: ' . $subscription->id .
                        '. Status: ' . $response['status']['status'] .
                        '. Message: ' . $statusMessage);
                }
            } catch (\Exception $e) {
                Log::error('An error occurred while processing subscription ID: ' . $subscription->id .
                    '. Error: ' . $e->getMessage());
            }
    }

    protected function calculateNextBillingDate($subscription): Carbon
    {
        $periodicity = $subscription->suscriptionPlan->periodicity;
        $nextBillingDate = Carbon::parse($subscription->next_billing_date);

        return match ($periodicity) {
            Periodicities::diario->value => $nextBillingDate->addDay(),
            Periodicities::quincenal->value => $nextBillingDate->addDays(15),
            Periodicities::mensual->value => $nextBillingDate->addMonth(),
            Periodicities::trimestral->value => $nextBillingDate->addMonths(3),
            Periodicities::semestral->value => $nextBillingDate->addMonths(6),
            Periodicities::anual->value => $nextBillingDate->addYear(),
            default => throw new \InvalidArgumentException('No se pudo calcular el siguiente pago'),
        };
    }

    /**
     * @throws Exception
     */
    private function charge(Suscription $subscription): array
    {

        $data = $subscription->toArray();
        $gatewayType = $subscription->initialPayment->payment_method;
        $gateway = PaymentFactory::create($gatewayType, $data);
        $paymentService = new PaymentGatewayService($gateway, $data);

        return $paymentService->collect();
    }
}
