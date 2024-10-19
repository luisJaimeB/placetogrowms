<?php

namespace Tests\Feature\Dasboard;

use App\Constants\Permissions;
use App\Constants\Roles;
use App\Constants\TypesSites;
use App\Models\Microsite;
use App\Models\Payment;
use App\Models\TypeSite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use HasPermissions;
    use RefreshDatabase;

    private const RESOURCE_NAME = 'dashboard';
    private string $route;
    private Role $admin;
    private Role $customer;
    private Permission $permission;
    private Permission $permission2;
    private TypeSite $typeSite;
    private Microsite $microsite;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->route = route(self::RESOURCE_NAME);

        $this->admin = Role::create(['name' => Roles::ADMIN->value]);
        $this->customer = Role::create(['name' => Roles::CUSTOMER->value]);
        $this->permission = Permission::create(['name' => Permissions::MICROSITES_INDEX->value]);
        $this->permission2 = Permission::create(['name' => Permissions::INVOICES_INDEX->value]);

        $this->admin->givePermissionTo($this->permission);
        $this->customer->givePermissionTo($this->permission2);

        $this->user = User::factory()->create();
        $this->user->assignRole($this->customer);
        $this->siteType = TypeSite::create(['name' => TypesSites::SITE_TYPE_INVOICE->value]);
        $this->microsite = Microsite::factory()->withTypeSiteId($this->siteType->id)->create(['user_id' => $this->user->id]);
    }

    public function admin_user_sees_all_payments(): void
    {
        $adminUser = User::factory()->create();
        $adminUser->assignRole($this->admin);
        $this->actingAs($adminUser);

        $payments = Payment::factory()->withMicrositeId($this->microsite)->count(3)->create();

        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Dashboard')
            ->has('payments', 3)
        );
    }

    public function test_non_admin_user_sees_own_payments(): void
    {
        $this->actingAs($this->user);

        $ownPayments = Payment::factory()->withMicrositeId($this->microsite)->count(2)->create();

        $otherPayments = Payment::factory()->withMicrositeId($this->microsite)->count(2)->create();

        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Dashboard')
            ->has('payments', 4)
        );
    }
}
