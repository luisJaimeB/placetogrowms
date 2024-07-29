<?php

namespace Database\Factories;

use App\Models\Category;
use App\Models\TypeSite;
use App\Models\User;
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
            'type_site_id' => TypeSite::factory()->create(),
            'category_id' => Category::factory()->create(),
            'expiration' => fake()->numberBetween(1, 100),
            'user_id' => User::factory()->create(),
        ];
    }
}
