<?php

namespace Database\Factories;

use App\Constants\Currencies;
use App\Models\Currency;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Currency>
 */
class CurrencyFactory extends Factory
{
    protected $model = Currency::class;
    public function definition(): array
    {
        $currency = $this->faker->unique()->randomElement([Currencies::USD, Currencies::COP]);
        return [
            'code' => $currency,
            'name' => $currency,
        ];
    }
}
