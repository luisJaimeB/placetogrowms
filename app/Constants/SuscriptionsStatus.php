<?php

namespace App\Constants;

use App\Concerns\EnumArrayable;
use App\Contracts\Arrayable;

enum SuscriptionsStatus: string implements Arrayable
{
    use EnumArrayable;

    case ACTIVE = 'Active';
    case INACTIVE = 'Inactive';
    case CANCELLED = 'Cancelled';
    case FREEZE = 'Freeze';
    case SUSPENDED = 'Suspended';
}
