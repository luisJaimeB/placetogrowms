<?php

namespace App\Services;

use App\Payments\PaymentMethod;

class PaymentGatewayService
{
    protected PaymentMethod $gateway;

    protected array $data;

    public function __construct(PaymentMethod $gateway, array $data)
    {
        $this->gateway = $gateway;
        $this->data = $data;
    }

    public function processPayment()
    {
        return $this->gateway->pay($this->data);
    }

    public function QueryPayment()
    {
        return $this->gateway->getInfomation($this->data);
    }

    public function cancelToken()
    {
        return $this->gateway->invalidateToken($this->data);
    }

    public function collect()
    {
        return $this->gateway->collect($this->data);
    }
}
