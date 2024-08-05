<?php

namespace Database\Seeders;

use App\Constants\Currencies;
use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        $currencies = [
            Currencies::USD,
            Currencies::COP,
        ];

        foreach ($currencies as $currency) {
            Currency::query()->create([
                'code' => $currency,
                'name' => $currency,
            ]);
        }
    }
}
