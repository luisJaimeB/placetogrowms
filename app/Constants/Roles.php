<?php

namespace App\Constants;

use ReflectionClass;

final class Roles
{
    public const string ADMIN = 'Admin';

    public const string CUSTOMER = 'customer';

    public const string SUBSCRIBER = 'subscriber';

    public static function toArray(): array
    {
        return (new ReflectionClass(self::class))->getConstants();
    }
}
