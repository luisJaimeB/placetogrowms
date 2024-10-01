<?php

namespace App\Payments;

interface PaymentMethod
{
    public function pay($data);

    public function getInfomation($payment);

    public function invalidateToken(array $data);
}
