<?php

namespace App\Constants;

use App\Concerns\EnumArrayable;
use App\Contracts\Arrayable;

enum BuyerIdTypes: string implements Arrayable
{
    use EnumArrayable;
    case CC = 'CC';
    case CE = 'CE';
    case TI = 'TI';
    case NIT = 'NIT';
    case RUT = 'RUT';

    public function documentType(): string
    {
        return match ($this) {
            self::CC => 'Cédula de ciudadanía',
            self::TI => 'Tarjeta de identidad',
            self::CE => 'Cédula de extranjería',
            self::NIT => 'Número de Identificación Tributaria',
            self::RUT => 'Registro único tributario',
        };
    }

    public static function toTypes(): array
    {
        return array_map(fn ($case) => [
            'code' => $case->value,
            'document_type' => $case->documentType(),
        ], self::cases());
    }
}
