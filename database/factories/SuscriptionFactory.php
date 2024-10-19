<?php

namespace Database\Factories;

use App\Constants\SuscriptionsStatus;
use App\Models\Microsite;
use App\Models\Payment;
use App\Models\Suscription;
use App\Models\SuscriptionPlan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SuscriptionFactory extends Factory
{
    protected $model = Suscription::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'payment_id' => null,
            'microsite_id' => null,
            'next_billing_date' => $this->faker->dateTimeBetween('+1 year', '+2 years')->format('Y-m-d'),
            'expiration_date' => $this->faker->dateTimeBetween('+1 year', '+2 years')->format('Y-m-d'),
            'status' => SuscriptionsStatus::active->value,
            'token' => Str::random(32),
            'plan_id' => SuscriptionPlan::factory(),
            'payer' => json_encode(['name' => $this->faker->name]),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function withMicrositeId($micrositeId): Factory|MicrositeFactory
    {
        return $this->state([
            'microsite_id' => $micrositeId,
        ]);
    }

    public function withPaymentId($paymentId): Factory
    {
        return $this->state([
            'payment_id' => $paymentId,
        ]);
    }
}