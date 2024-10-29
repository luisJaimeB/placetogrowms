<?php

namespace Database\Seeders;

use App\Constants\TypesSites;
use App\Models\TypeSite;
use Illuminate\Database\Seeder;

class TypeSiteSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            TypesSites::SITE_TYPE_DONATION->value,
            TypesSites::SITE_TYPE_INVOICE->value,
            TypesSites::SITE_TYPE_SUBSCRIPTION->value,
        ];

        foreach ($types as $type) {
            TypeSite::query()->create([
                'name' => $type,
            ]);
        }
    }
}
