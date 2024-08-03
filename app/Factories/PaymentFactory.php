<?php

namespace App\Factories;

use App\Payments\PayPalGateway;
use App\Payments\PlaceToPayGateway;
use Exception;

class PaymentFactory
{
    /**
     * @throws Exception
     */
    public static function create($type, array $data): PayPalGateway|PlaceToPayGateway
    {
        return match ($type) {
            'paypal' => new PayPalGateway(),
            'placetopay' => new PlaceToPayGateway($data),
            default => throw new Exception("Payment method not supported."),
        };
    }
}
