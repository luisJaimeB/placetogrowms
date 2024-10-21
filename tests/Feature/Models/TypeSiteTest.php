<?php

namespace Tests\Feature\Models;

use App\Models\Microsite;
use App\Models\TypeSite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TypeSiteTest extends TestCase
{
    use RefreshDatabase;

    public function test_type_site_has_many_microsites()
    {
        $typeSite = TypeSite::factory()->create();

        $microsites = Microsite::factory()->for($typeSite)->count(3)->create(['type_site_id' => $typeSite->id]);

        $this->assertCount(3, $typeSite->microsites);
        $this->assertInstanceOf(Microsite::class, $typeSite->microsites->first());
    }
}
