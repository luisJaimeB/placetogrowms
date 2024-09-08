<?php

namespace App\Constants;

use ReflectionClass;

class Optionalfields
{
    public const array ADDRESS = [
        'label' => 'address',
        'field' => 'address',
        'rule' => 'required|regex:/^\d+\s[A-Za-z0-9\s,\'\-\.#]+$/',
    ];

    public const array CITY = [
        'label' => 'city',
        'field' => 'city',
        'rule' => 'required|string|max:255',
    ];

    public const array COUNTRY = [
        'label' => 'country',
        'field' => 'country',
        'rule' => 'required|string|max:255',
    ];

    public static function toArray(): array
    {
        return (new ReflectionClass(self::class))->getConstants();
    }
}
