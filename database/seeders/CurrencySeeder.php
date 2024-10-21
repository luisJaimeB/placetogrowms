<?php

namespace Database\Seeders;

use App\Constants\Currencies;
use App\Models\Currency;
use Illuminate\Database\Seeder;

class CurrencySeeder extends Seeder
{
    public function run(): void
    {
        $currencies = Currencies::toArray();

        foreach ($currencies as $currency) {
            Currency::query()->create([
                'code' => $currency,
                'name' => $currency,
            ]);
        }
    }
}
