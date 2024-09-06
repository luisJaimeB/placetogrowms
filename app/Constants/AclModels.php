<?php

namespace App\Constants;

use App\Concerns\EnumArrayable;
use App\Contracts\Arrayable;

enum AclModels: string implements Arrayable
{
    use EnumArrayable;

    case Microsites = 'microsites';
    case Invoices = 'invoices';

    public static function toOptions(): array
    {
        return [
            [
                'value' => self::Microsites->value,
                'text' => 'Microsites',
            ],
            [
                'value' => self::Invoices->value,
                'text' => 'Invoices',
            ]
        ];
    }

    public function columns(): array
    {
        return match ($this){
            self::Microsites => ['id', 'name'],
            self::Invoices => ['id', 'order_number'],
        };
    }

    public function columAliases(): array
    {
        return match ($this){
            self::Microsites => ['name' => 'text'],
            self::Invoices => ['order_number' => 'text'],
        };
    }

    public function applyColumnAliases(array $data): array
    {
        $aliases = $this->columAliases();
        $result = [];

        foreach ($data as $key => $value) {
            $aliasKey = $aliases[$key] ?? $key;
            $result[$aliasKey] = $value;
        }

        return $result;
    }
}
