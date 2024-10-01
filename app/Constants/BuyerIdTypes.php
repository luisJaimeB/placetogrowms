<?php

namespace App\Constants;

use ReflectionClass;

class BuyerIdTypes
{
    public const array CC = [
        'code' => 'CC',
        'document_type' => 'Cédula de ciudadanía',
    ];

    public const array CE = [
        'code' => 'CE',
        'document_type' => 'Cédula de extranjería',
    ];

    public const array TI = [
        'code' => 'TI',
        'document_type' => 'Tarjeta de identidad',
    ];

    public const array NIT = [
        'code' => 'NIT',
        'document_type' => 'Número de Identificación Tributaria	',
    ];

    public const array RUT = [
        'code' => 'RUT',
        'document_type' => 'Registro único tributario',
    ];

    public static function toArray(): array
    {
        return (new ReflectionClass(self::class))->getConstants();
    }
}
