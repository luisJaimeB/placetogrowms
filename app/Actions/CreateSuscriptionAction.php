<?php

namespace App\Actions;

use App\Constants\Periodicity;
use App\Constants\SubscriptionTerm;
use App\Constants\SuscriptionsStatus;
use App\Contracts\Create;
use App\Models\Suscription;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CreateSuscriptionAction implements Create
{
    public static function execute(array $data): Model|false
    {
        $suscription = new Suscription;
        $suscription->payer = $data['request']['payer'];
        $token = null;
        $periodicity = $data['plan'][0]['periodicity'];

        foreach ($data['subscription']['instrument'] as $instrument) {
            if ($instrument['keyword'] === 'token') {
                $token = $instrument['value'];
                break;
            }
        }
        $suscription->token = $token;
        $suscription->plan_id = $data['plan'][0]['id'];
        $suscription->user_id = $data['user']['id'];
        $suscription->microsite_id = $data['plan'][0]['microsite_id'];
        $suscription->payment_id = $data['payment_id'];
        $suscription->next_billing_date = self::nextBilling($periodicity);
        $suscription->expiration_date = self::expirationDate($data['plan'][0]['subscriptionTerm']);
        $suscription->status = SuscriptionsStatus::ACTIVE;
        $suscription->save();

        return $suscription;
    }

    private static function nextBilling(string $periodicity): string
    {
        $today = Carbon::today();

        return match ($periodicity) {
            Periodicity::Daily->value => $today->addDay()->toDateString(),
            Periodicity::Biweekly->value => $today->addDays(15)->toDateString(),
            Periodicity::Monthly->value => $today->addMonth()->toDateString(),
            Periodicity::Quarterly->value => $today->addMonths(3)->toDateString(),
            Periodicity::Semester->value => $today->addMonths(6)->toDateString(),
            Periodicity::Annual->value => $today->addYear()->toDateString(),
            default => throw new \InvalidArgumentException("Periodicidad no válida: $periodicity"),
        };
    }

    private static function expirationDate(string $subscriptionTerm): Carbon
    {
        $today = Carbon::today();

        switch ($subscriptionTerm) {
            case SubscriptionTerm::monthly->value:
                return $today->addMonth();
            case SubscriptionTerm::trimester->value:
                return $today->addMonths(3);
            case SubscriptionTerm::semester->value:
                return $today->addMonths(6);
            case SubscriptionTerm::annual->value:
                return $today->addYear();
            default:
                throw new \InvalidArgumentException('Invalid subscription term');
        }
    }
}
