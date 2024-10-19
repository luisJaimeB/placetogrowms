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
            $payment = new Payment;
            $payment->currency_id = $data['currency'];
            $payment->type = $data['type'];
            $payment->payment_method = $data['paymentMethod'];
            $payment->microsite_id = $data['micrositeId'];

            if (isset($data['suscriptionId'])){
                $payment->suscription_id = $data['suscriptionId'];
            }

            if (isset($data['plan'])){
                $payment->plan = $data['plan'];
            }

            if (isset($data['payment']['autorization'])){
                $payment->cus_code = $data['payment']['autorization'];
            }

            if (isset($data['request']['ipAddress'])){
                $payment->ip_address = $data['request']['ipAddress'];
            }

            if (isset($data['request']['userAgent'])){
                $payment->user_agent = $data['request']['userAgent'];
            }

            if (isset($data['request']['payer'])){
                $payment->payer = self::PayerCast($data['request']['payer']);
            }

            if (isset($data['buyer'])){
                $payment->buyer = $data['buyer'];
            } else {
                $payment->buyer = self::buyerCast($data);
            }

            if (isset($data['status']['date'])){
                $payment->date = $data['status']['date'];
            }

            if (isset($data['request']['payment']['description'])){
                $payment->description = $data['request']['payment']['description'];
            } else {
                $payment->description = $data['description'];
            }

            if (isset($data['request']['payment']['amount']['total'])){
                $payment->amount = $data['request']['payment']['amount']['total'];
            } else {
                $payment->amount = $data['amount'];
            }

            if ($data['type'] === 3) {
                $payment->plan = $data['plan'];
            }

            if (isset($data['status']['status'])){
                $payment->status = $data['status']['status'];
            }

            if (isset($data['payment']['reference'])){
                $payment->reference = $data['payment']['reference'];
            }

            if (isset($data['requestId'])){
                $payment->request_id = $data['requestId'];
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

    private static function payerCast(array $data): false|string
    {
        $dataPayer = [
            'document_type' => $data['documentType'],
            'document' => $data['document'],
            'name' => $data['name'],
            'surname' => $data['surname'],
            'email' => $data['email'],
            'mobile' => $data['mobile'],
        ];

        return json_encode($dataPayer);
    }
}
