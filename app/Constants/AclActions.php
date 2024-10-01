<?php

namespace App\Constants;

use App\Concerns\EnumArrayable;
use App\Contracts\Arrayable;

enum AclActions: string implements Arrayable
{
    use EnumArrayable;

    case allowed = 'allowed';
    case denied = 'denied';
}
