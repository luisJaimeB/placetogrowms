<?php

namespace App\Constants;

use App\Contracts\Arrayable;
use App\Concerns\EnumArrayable;

enum Periodicities: string implements Arrayable
{
    use EnumArrayable;

    case diario = 'diario';
    case quincenal = 'quincenal';
    case mensual = 'mensual';
    case trimestral = 'trimestral';
    case semestral = 'semestral';
    case anual = 'anual';
}
