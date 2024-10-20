<?php

namespace Tests\Feature\Plans;

use App\Constants\Periodicity;
use App\Constants\Permissions;
use App\Constants\Roles;
use App\Constants\SubscriptionTerm;
use App\Constants\TypesSites;
use App\Models\Microsite;
use App\Models\SuscriptionPlan;
use App\Models\TypeSite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;
use Tests\TestCase;

class UpdatePlanTest extends TestCase
{
    use HasPermissions;
    use RefreshDatabase;
    use WithFaker;

    private const RESOURCE_NAME = 'planes.update';
    private string $route;
    private Role $adminrole;
    private Permission $permission;
    private User $user;
    private Microsite $microsite;
    private SuscriptionPlan $plan;
    private TypeSite $typeSite;

    protected function setUp(): void
    {
        parent::setUp();

        $this->siteType = TypeSite::create(['name' => TypesSites::SITE_TYPE_SUBSCRIPTION->value]);
        $this->microsite = Microsite::factory()->withTypeSiteId($this->siteType->id)->create();

        $this->plan = SuscriptionPlan::factory()->withMicrositeId($this->microsite->id)->create();
        $this->route = route(self::RESOURCE_NAME, $this->plan);

        $this->user = User::factory()->create();
        $this->adminrole = Role::create(['name' => Roles::ADMIN->value]);
        $this->permission = Permission::create(['name' => Permissions::PLANES_UPDATE->value]);

        $this->adminrole->givePermissionTo($this->permission);
        $this->user->assignRole($this->adminrole);
    }

    public function test_guest_user_can_not_access_to_planes_update(): void
    {
        $response = $this->patch($this->route);

        $response->assertRedirect('login');
    }

    public function test_unauthorized_user_cant_access_to_planes_update(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->patch($this->route);

        $response->assertForbidden();
    }

    public function test_authorized_user_can_update_a_plan(): void
    {
        Carbon::setTestNow(now());

        $data = [
            'name' => $this->faker->word.' '.$this->faker->word,
            'periodicity' => Periodicity::Daily->value,
            'amount' => $this->faker->numberBetween(1, 9999999999),
            'microsite_id' => $this->microsite->id,
            'attempts' => 2,
            'lapse' => 6,
            'subscriptionTerm' => $this->faker->randomElement(SubscriptionTerm::toArray()),
            'items' => ['item50', 'item51'],
            'user_id' => $this->user->id,
        ];

        $response = $this->actingAs($this->user)
            ->patch($this->route, $data);

        $response->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('planes.index'));

        $this->assertDatabaseHas('suscription_plans', [
            'name' => $data['name'],
            'periodicity' => $data['periodicity'],
        ]);
    }
}
