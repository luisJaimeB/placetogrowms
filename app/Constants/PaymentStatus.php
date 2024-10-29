<?php

namespace App\Constants;

use App\Concerns\EnumArrayable;
use App\Contracts\Arrayable;

enum PaymentStatus: string implements Arrayable
{
    use EnumArrayable;
    case APPROVED = 'APPROVED';

    case PENDING = 'PENDIGN';

    case REJECTED = 'REJECTED';
}
