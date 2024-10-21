<?php

namespace App\Constants;

use App\Concerns\EnumArrayable;
use App\Contracts\Arrayable;

enum Periodicity: string implements Arrayable
{
    use EnumArrayable;

    case Daily = 'daily';
    case Biweekly = 'biweekly';
    case Monthly = 'monthly';
    case Quarterly = 'quarterly';
    case Semester = 'semester';
    case Annual = 'annual';
}
