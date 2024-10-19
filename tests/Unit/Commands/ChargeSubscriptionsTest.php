<?php

namespace Tests\Unit\Commands;

use App\Constants\SuscriptionsStatus;
use App\Constants\TypesSites;
use App\Models\Microsite;
use App\Models\Payment;
use App\Models\Suscription;
use App\Models\SuscriptionPlan;
use App\Models\TypeSite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ChargeSubscriptionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_subscription_is_suspended_when_collect_fails(): void
    {
        $now = now();

        Carbon::setTestNow($now);

        $siteType = TypeSite::factory()->create(['name' => TypesSites::SITE_TYPE_SUBSCRIPTION->value]);
        $microsite = Microsite::factory()->for($siteType, 'typeSite')->create();
        $plan = SuscriptionPlan::factory()->for($microsite)->create();
        $payment = Payment::factory()->for($microsite)->create();
        $subscription = Suscription::factory()
            ->for($microsite)
            ->for($plan, 'suscriptionPlan')
            ->for($payment, 'initialPayment')
            ->create();

        $failedResponse = [
            "requestId" => 1,
            "status" => [
                "status" => "REJECTED",
                "reason" => "XN",
                "message" => "Se ha rechazado la petición",
                "date" => "2021-11-30T16:44:24-05:00"
            ],
        ];


        Http::fake(fn (Request $request) => Http::response(json_encode($failedResponse), 200));

        $this->artisan('subscriptions:charge')
            ->assertOk();

        $this->assertDatabaseHas('suscriptions', [
            'id' => $subscription->id,
            'recovery_count' => 1,
        ]);

        $this->travel(1)->day();

        $this->artisan('subscriptions:charge')
            ->assertOk();

        $this->assertDatabaseHas('suscriptions', [
            'id' => $subscription->id,
            'recovery_count' => 2,
        ]);

        $this->travel(1)->day();

        $this->artisan('subscriptions:charge')
            ->assertOk();

        $this->assertDatabaseHas('suscriptions', [
            'id' => $subscription->id,
            'recovery_count' => 3,
            'status' => SuscriptionsStatus::SUSPENDED->value,
        ]);
    }
}
