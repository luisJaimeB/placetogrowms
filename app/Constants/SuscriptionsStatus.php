<?php

namespace App\Constants;

use App\Concerns\EnumArrayable;
use App\Contracts\Arrayable;

enum SuscriptionsStatus: string implements Arrayable
{
    use EnumArrayable;

    case active = 'Activo';
    case inactivo = 'Inactivo';
    case canceled = 'Cancelado';
}
