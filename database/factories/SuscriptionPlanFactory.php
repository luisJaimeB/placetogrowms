<?php

namespace Database\Factories;

use App\Constants\Periodicities;
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
            'periodicity' => $this->faker->randomElement(Periodicities::toArray()),
            'interval' => $this->faker->word,
            'amount' => $this->faker->numberBetween(1, 9999999999),
            'next_payment' => $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
            'due_date' => $this->faker->dateTimeBetween('+1 year', '+2 years')->format('Y-m-d'),
            'microsite_id' => Microsite::factory(),
            'items' => json_encode(['item1', 'item2']),
            'user_id' => User::factory(),
        ];
    }
}
