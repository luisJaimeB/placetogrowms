<?php

namespace Database\Seeders;

use App\Constants\BuyerIdTypes;
use App\Models\BuyerIdType;
use Illuminate\Database\Seeder;

class BuyerIdTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $buyerIdTypes = BuyerIdTypes::toTypes();

        foreach ($buyerIdTypes as $buyerIdType) {
            BuyerIdType::query()->create([
                'code' => $buyerIdType['code'],
                'document_type' => $buyerIdType['document_type'],
            ]);
        }
    }
}
