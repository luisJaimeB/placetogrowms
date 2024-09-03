<?php

namespace App\Concerns;

trait EnumArrayable
{
    public static function toArray(): array
    {
        return array_column(self::cases(),'value');
    }
}
