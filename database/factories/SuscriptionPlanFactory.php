<?php

namespace Database\Factories;

use App\Constants\Periodicity;
use App\Constants\SubscriptionTerm;
use App\Models\Microsite;
use App\Models\SuscriptionPlan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class SuscriptionPlanFactory extends Factory
{
    protected $model = SuscriptionPlan::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->word.' '.$this->faker->word,
            'periodicity' => $this->faker->randomElement(Periodicity::toArray()),
            'amount' => $this->faker->numberBetween(1, 9999999999),
            'microsite_id' => null,
            'attempts' => 3,
            'lapse' => 8,
            'subscriptionTerm' => $this->faker->randomElement(SubscriptionTerm::toArray()),
            'items' => json_encode(['item1', 'item2']),
            'user_id' => User::factory(),
        ];
    }

    public function withMicrositeId($micrositeId): Factory|MicrositeFactory
    {
        return $this->state([
            'microsite_id' => $micrositeId,
        ]);
    }
}
