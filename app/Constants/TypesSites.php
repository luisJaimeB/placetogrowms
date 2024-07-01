<?php

namespace App\Constants;

use ReflectionClass;

final class TypesSites
{
    public const string SITE_TYPE_INVOICE = 'Invoice';
    public const string SITE_TYPE_SUBSCRIPTION = 'Subscription';
    public const string SITE_TYPE_DONATION = 'Donation';
    
    public static function toArray (): array
    {
        return (new ReflectionClass(self::class))->getConstants();
    }
} 