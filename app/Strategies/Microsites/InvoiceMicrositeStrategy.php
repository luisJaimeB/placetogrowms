<?php

namespace App\Strategies\Microsites;

use App\Models\Microsite;
use App\Strategies\Microsites\MicrositeStrategy;

class InvoiceMicrositeStrategy implements MicrositeStrategy
{

    public function renderComponent(Microsite $microsite): string
    {
        return 'Payments/InvoicePayment';
    }
}
