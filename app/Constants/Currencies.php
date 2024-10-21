<?php

namespace App\Constants;

use App\Concerns\EnumArrayable;
use App\Contracts\Arrayable;

enum Currencies: string implements Arrayable
{
    use EnumArrayable;
    case USD = 'USD';
    case COP = 'COP';
}
