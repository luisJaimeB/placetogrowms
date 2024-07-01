<?php

namespace Database\Seeders;

use App\Constants\TypesSites;
use App\Models\TypeSite;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TypeSiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        TypeSite::create([
            'name' => TypesSites::SITE_TYPE_DONATION,
        ]);

        TypeSite::create([
            'name' => TypesSites::SITE_TYPE_INVOICE,
        ]);

        TypeSite::create([
            'name' => TypesSites::SITE_TYPE_SUBSCRIPTION,
        ]);
    }
}
