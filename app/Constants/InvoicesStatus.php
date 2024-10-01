<?php

namespace App\Constants;

use App\Concerns\EnumArrayable;
use App\Contracts\Arrayable;

enum InvoicesStatus: string implements Arrayable
{
    use EnumArrayable;

    case active = 'ACTIVE';
    case expired = 'EXPIRED';
    case paid = 'PAY';
}
