<?php

namespace App\Actions;

use App\Constants\SuscriptionsStatus;
use App\Contracts\Create;
use App\Models\Suscription;
use Illuminate\Database\Eloquent\Model;

class CreateSuscriptionAction implements Create
{

    public static function execute(array $data): Model|false
    {
        $suscription = new Suscription();
        $suscription->payer = $data['request']['payer'];
        $token = null;

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
        $suscription->status = SuscriptionsStatus::active;
        $suscription->save();

        return $suscription;
    }
}
