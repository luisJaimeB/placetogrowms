<?php

namespace App\Constants;

use App\Concerns\EnumArrayable;
use App\Contracts\Arrayable;

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
