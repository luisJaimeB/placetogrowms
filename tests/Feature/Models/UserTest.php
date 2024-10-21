<?php

namespace Tests\Feature\Models;

use App\Models\Currency;
use App\Models\Invoice;
use App\Models\Microsite;
use App\Models\SuscriptionPlan;
use App\Models\TypeSite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UserTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_has_many_microsites()
    {
        $user = User::factory()->create();
        $siteType = TypeSite::factory()->create();
        $microsite = Microsite::factory()->for($siteType, 'typeSite')->create(['user_id' => $user->id]);

        $this->assertInstanceOf(Microsite::class, $user->microsites()->first());
        $this->assertCount(1, $user->microsites);
    }

    public function test_user_has_many_suscription_planes()
    {
        $user = User::factory()->create();
        $siteType = TypeSite::factory()->create();
        $microsite = Microsite::factory()->for($siteType, 'typeSite')->create(['user_id' => $user->id]);
        $suscriptionPlan = SuscriptionPlan::factory()->for($microsite)->create(['user_id' => $user->id]);

        $this->assertInstanceOf(SuscriptionPlan::class, $user->suscriptionPlanes()->first());
        $this->assertCount(1, $user->suscriptionPlanes);
    }

    public function test_user_has_many_invoices()
    {
        $user = User::factory()->create();
        $siteType = TypeSite::factory()->create();
        $currency = Currency::factory()->create();
        $microsite = Microsite::factory()->for($siteType, 'typeSite')->create(['user_id' => $user->id]);
        $invoice = Invoice::factory()
            ->for($microsite)
            ->for($currency)
            ->create(['user_id' => $user->id]);

        $this->assertInstanceOf(Invoice::class, $user->invoices()->first());
        $this->assertCount(1, $user->invoices);
    }
}
