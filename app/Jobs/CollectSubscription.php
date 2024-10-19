<?php

namespace App\Jobs;

use App\Actions\CreatePaymentAction;
use App\Constants\PaymentStatus;
use App\Constants\Periodicities;
use App\Constants\SuscriptionsStatus;
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

    protected Suscription $subscription;

    public function __construct(Suscription $subscription)
    {
        $this->subscription = $subscription;
    }

    public function handle(): void
    {
        try {
            $response = $this->charge($this->subscription);
            $response['type'] = $this->subscription->microsite->type_site_id;
            $response['currency'] = $this->subscription->microsite->currencies()->first()->id ?? null;
            $response['buyer'] = $this->subscription->initialPayment->buyer;
            $response['micrositeId'] = $this->subscription->microsite->id;
            $response['paymentMethod'] = $this->subscription->initialPayment->payment_method;
            $response['plan'] = $this->subscription->suscriptionPlan->id;
            $response['suscriptionId'] = $this->subscription->id;

            CreatePaymentAction::execute($response);

            if (isset($response['status']['status']) && $response['status']['status'] === PaymentStatus::APPROVED->value) {
                $this->subscription->recovery_count = 0;
                $this->subscription->status = SuscriptionsStatus::ACTIVE;
                $nextBillingDate = $this->calculateNextBillingDate($this->subscription);
                $this->subscription->next_billing_date = $nextBillingDate;
                $this->subscription->save();

                Log::info('Pago aprobado para la suscripción ID: ' . $this->subscription->id);

            } elseif (isset($response['status']['status']) && $response['status']['status'] === PaymentStatus::REJECTED->value) {
                $this->subscription->recovery_count++;
                $maxAttempts = $this->subscription->suscriptionPlan->attempts;

                if ($this->subscription->recovery_count >= $maxAttempts) {
                    $this->subscription->status = SuscriptionsStatus::SUSPENDED;
                    Log::info('Estado del pago actualizado en: ' . $this->subscription->status->value);
                } else {
                    $this->subscription->status = SuscriptionsStatus::FREEZE;
                    Log::info('Estado del pago actualizado en: ' . $this->subscription->status->value);
                }

                $this->subscription->save();

                Log::info('Pago rechazado para la suscripción ID: ' . $this->subscription->id . '. Contador de recobros: ' . $this->subscription->recovery_count);
            }

        } catch (Exception $e) {
            Log::error('Error procesando la suscripción ID: ' . $this->subscription->id . '. Error: ' . $e->getMessage());
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
