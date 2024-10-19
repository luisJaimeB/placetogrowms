<?php

namespace Tests\Feature\Subscriptions;

use AllowDynamicProperties;
use App\Constants\Roles;
use App\Constants\TypesSites;
use App\Models\Microsite;
use App\Models\Payment;
use App\Models\Suscription;
use App\Models\SuscriptionPlan;
use App\Models\TypeSite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

#[AllowDynamicProperties] class SuscriptionControllerTest extends TestCase
{
    use RefreshDatabase;

    private Microsite $microsite;
    private TypeSite $typeSite;
    private Payment $payment;
    private SuscriptionPlan $plan;
    private Role $adminrole;

    #[Test]
    public function it_shows_all_suscriptions_for_admin(): void
    {
        $this->adminrole = Role::create(['name' => Roles::ADMIN]);
        $admin = User::factory()->create();
        $admin->assignRole($this->adminrole);

        $this->siteType = TypeSite::create(['name' => TypesSites::SITE_TYPE_SUBSCRIPTION->value]);
        $this->microsite = Microsite::factory()->withTypeSiteId($this->siteType->id)->create();
        $this->plan = SuscriptionPlan::factory()->withMicrositeId($this->microsite->id)->create();

        Suscription::factory()->withMicrositeId($this->microsite)->count(3)->create();

        $this->actingAs($admin)
            ->get(route('suscriptions.index'))
            ->assertStatus(200)
            ->assertInertia(fn ($page) => $page
                ->component('Suscriptions/Index')
                ->has('suscriptions', 3)
                ->where('editing', true)
            );
    }

    #[Test]
    public function it_shows_user_suscriptions(): void
    {
        $user = User::factory()->create();
        $this->siteType = TypeSite::create(['name' => TypesSites::SITE_TYPE_SUBSCRIPTION->value]);
        $this->microsite = Microsite::factory()->withTypeSiteId($this->siteType->id)->create();
        $this->plan = SuscriptionPlan::factory()->withMicrositeId($this->microsite->id)->create();
        $this->payment = Payment::factory()->withMicrositeId($this->microsite)->withSubscriptionId($this->plan)->create();
        $suscriptions = Suscription::factory()->withMicrositeId($this->microsite)
            ->withPaymentId($this->payment)
            ->count(2)->create(['user_id' => $user->id]);

        $this->actingAs($user)
            ->get(route('suscriptions.index'))
            ->assertStatus(200)
            ->assertInertia(fn ($page) => $page
                ->component('Suscriptions/Index')
                ->has('suscriptions', 2)
                ->where('editing', false)
            );
    }
}
