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
            TypesSites::SITE_TYPE_DONATION,
            TypesSites::SITE_TYPE_INVOICE,
            TypesSites::SITE_TYPE_SUBSCRIPTION,
        ];

        foreach ($types as $type) {
            TypeSite::query()->create([
                'name' => $type,
            ]);
        }
    }
}
