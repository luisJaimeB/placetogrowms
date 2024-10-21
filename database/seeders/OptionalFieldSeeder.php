<?php

namespace Database\Seeders;

use App\Constants\OptionalFields;
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
            OptionalFields::CITY,
            OptionalFields::ADDRESS,
            OptionalFields::COUNTRY,
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
