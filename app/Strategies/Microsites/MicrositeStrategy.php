<?php

namespace App\Strategies\Microsites;

use App\Models\Microsite;

interface MicrositeStrategy
{
    public function renderComponent(Microsite $microsite): string;
}
