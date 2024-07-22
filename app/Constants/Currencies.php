<?php

namespace App\Constants;

class Currencies
{
    public const string USD = 'USD';
    public const string COP = 'COP';

    public static function toArray (): array
    {
        return (new ReflectionClass(self::class))->getConstants();
    }
}
