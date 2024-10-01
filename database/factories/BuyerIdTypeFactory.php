<?php

namespace Database\Factories;

use App\Models\BuyerIdType;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Carbon;

class BuyerIdTypeFactory extends Factory
{
    protected $model = BuyerIdType::class;

    public function definition(): array
    {
        return [
            'code' => $this->faker->word(),
            'document_type' => $this->faker->word(),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ];
    }
}
