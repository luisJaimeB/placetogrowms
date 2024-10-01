<?php

namespace Database\Factories;

use App\Constants\InvoicesStatus;
use App\Models\BuyerIdType;
use App\Models\Currency;
use App\Models\Invoice;
use App\Models\Microsite;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition(): array
    {
        $currencyIds = Currency::pluck('id')->toArray();

        return [
            'status' => InvoicesStatus::active,
            'order_number' => Str::random(32),
            'debtor_name' => $this->faker->words(2, true),
            'microsite_id' => Microsite::factory(),
            'identification_type_id' => BuyerIdType::factory(),
            'identification_number' => $this->faker->randomFloat(0, 0, 9999999999.99),
            'email' => $this->faker->safeEmail,
            'description' => $this->faker->sentence(10),
            'amount' => $this->faker->randomFloat(2, 0, 9999999999.99),
            'currency_id' => $this->faker->randomElement($currencyIds),
            'expiration_date' => $this->faker->dateTimeBetween('+1 year', '+2 years')->format('Y-m-d'),
            'user_id' => User::factory(),
            'payment_id' => null,
        ];
    }
}
