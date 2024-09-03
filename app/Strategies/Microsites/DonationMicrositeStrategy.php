<?php

namespace App\Strategies\Microsites;

use App\Models\Microsite;

class DonationMicrositeStrategy implements MicrositeStrategy
{

    public function renderComponent(Microsite $microsite): string
    {
        return 'Payments/CreatePayment';
    }
}
