<?php

namespace App\Constants;

use App\Concerns\EnumArrayable;
use App\Contracts\Arrayable;

enum SubscriptionTerm: string implements Arrayable
{
    use EnumArrayable;

    case semester = 'semester';
    case annual = 'annual';
    case trimester = 'trimester';
    case monthly = 'monthly';

}
