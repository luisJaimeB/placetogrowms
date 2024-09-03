<?php

namespace App\Strategies\Microsites;

use App\Models\Microsite;

class MicrositeContext
{
    protected MicrositeStrategy $strategy;

    public function setStrategy(MicrositeStrategy $strategy): void
    {
        $this->strategy = $strategy;
    }

    public function renderComponent(Microsite $microsite): string
    {
        return $this->strategy->renderComponent($microsite);
    }
}
