<?php

namespace App\Actions;

use App\Contracts\Executable;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Model;
use Throwable;

class CreatePaymentAction implements Executable
{
    /**
     * @throws Throwable
     */
    public static function execute(array $data, ?Model $model = null): Model|false
    {
        try {
            $payment = new Payment();
            $payment->description = $data['description'];
            $payment->amount = $data['amount'];
            $payment->currency_id = $data['currency'];
            $payment->type = $data['type'];
            $payment->buyer = self::buyerCast($data);
            $payment->payment_method = $data['paymentMethod'];
            $payment->microsite_id = $data['micrositeId'];
            if ($data['type'] === 3) {
                $payment->plan = $data['plan'];
            }

            $payment->save();

            return $payment;
        } catch (Throwable $e) {
            report($e);
            throw $e;
        }
    }

    private static function buyerCast(array $data): false|string
    {
        $dataBuyer = [
            'buyer_id_type' => $data['buyer_id_type'],
            'buyer_id' => $data['buyer_id'],
            'name' => $data['name'],
            'lastName' => $data['lastName'],
            'email' => $data['email'],
            'phone' => $data['phone'],
        ];

        return json_encode($dataBuyer);
    }
}
