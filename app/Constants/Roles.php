<?php

namespace App\Constants;

use App\Concerns\EnumArrayable;
use App\Contracts\Arrayable;

enum Roles: string implements Arrayable
{
    use EnumArrayable;

    case ADMIN = 'Admin';
    case CUSTOMER = 'customer';
    case SUBSCRIBER = 'subscriber';
}
