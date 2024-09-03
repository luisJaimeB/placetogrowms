<?php

namespace App\Actions;

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
        $suscription->plan = $data['plan'];
        $suscription->user_id = $data['user']['id'];
        $suscription->save();

        return $suscription;
    }
}
