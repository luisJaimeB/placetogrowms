<?php

namespace App\Strategies\Microsites;

use App\Models\Microsite;

class SubscriptionMicrositeStrategy implements MicrositeStrategy
{
    public function renderComponent(Microsite $microsite): string
    {
        return 'Payments/SubscriptionPayment';
    }
}
