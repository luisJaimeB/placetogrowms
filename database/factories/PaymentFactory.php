<?php

namespace Database\Factories;

use App\Constants\PaymentStatus;
use App\Models\Currency;
use App\Models\Microsite;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $return_id = $this->faker->sentence(1);

        return [
            'status' => $this->faker->randomElement([PaymentStatus::APPROVED, PaymentStatus::PENDING, PaymentStatus::REJECTED]),
            'request_id' => $this->faker->numberBetween(10000, 99999),
            'type' => $this->faker->numberBetween(1, 3),
            'amount' => $this->faker->numberBetween(1000, 800000),
            'currency_id' => function () {
                return Currency::firstOrCreate(
                    ['code' => 'USD'],
                    ['name' => 'US Dollar']
                )->id;
            },
            'reference' => $this->faker->sentence(1),
            'description' => $this->faker->text(100),
            'date' => $this->faker->date(),
            'buyer' => json_encode([
                'name' => $this->faker->name,
                'lastName' => $this->faker->lastName,
                'email' => $this->faker->email,
                'phone' => $this->faker->phoneNumber,
            ]),
            'return_id' => $return_id,
            'return_url' => route('payments.return', $return_id),
            'microsite_id' => null,
            'payment_method' => 'placetopay',
            'suscription_id' => null,
        ];
    }

    public function withMicrositeId($micrositeId): Factory|MicrositeFactory
    {
        return $this->state([
            'microsite_id' => $micrositeId,
        ]);
    }

    public function withSubscriptionId($subscriptionId)
    {
        return $this->state([
            'suscription_id' => $subscriptionId
        ]);
    }
}
