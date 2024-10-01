<?php

namespace Database\Seeders;

use App\Constants\Optionalfields;
use App\Models\OptionalField;
use Illuminate\Database\Seeder;

class OptionalFieldSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $optionalFields = [
            Optionalfields::CITY,
            Optionalfields::ADDRESS,
            Optionalfields::COUNTRY,
        ];

        foreach ($optionalFields as $optionalField) {
            Optionalfield::query()->create([
                'label' => $optionalField['label'],
                'field' => $optionalField['field'],
                'rule' => $optionalField['rule'],
            ]);
        }
    }
}
