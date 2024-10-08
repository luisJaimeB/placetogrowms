<?php

namespace App\Constants;

use App\Concerns\EnumArrayable;
use App\Contracts\Arrayable;
use ReflectionClass;

enum TypesSites: string implements Arrayable
{
    use EnumArrayable;

    case SITE_TYPE_INVOICE = 'Invoice';
    case SITE_TYPE_SUBSCRIPTION = 'Subscription';
    case SITE_TYPE_DONATION = 'Donation';
}
