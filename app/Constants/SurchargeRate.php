<?php

namespace App\Constants;

use App\Concerns\EnumArrayable;
use App\Contracts\Arrayable;

enum SurchargeRate: string implements Arrayable
{

    use EnumArrayable;

    case PERCENT = 'percent';
    case ADDITIONAL_AMOUNT = 'additional amount';
}
