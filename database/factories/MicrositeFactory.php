<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Microsite>
 */
class MicrositeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'type_site_id' => fake()->numberBetween(1, 3),
            'category_id' => fake()->numberBetween(1, 100),
            'expiration' => fake()->numberBetween(1, 100),
        ];
    }
}
