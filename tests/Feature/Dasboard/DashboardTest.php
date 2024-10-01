<?php

namespace Tests\Feature\Dasboard;

use App\Constants\Permissions;
use App\Constants\Roles;
use App\Models\Microsite;
use App\Models\Payment;
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

    protected function setUp(): void
    {
        parent::setUp();

        $this->route = route(self::RESOURCE_NAME);

        $this->admin = Role::create(['name' => Roles::ADMIN]);
        $this->customer = Role::create(['name' => Roles::CUSTOMER]);
        $this->permission = Permission::create(['name' => Permissions::MICROSITES_INDEX]);
        $this->permission2 = Permission::create(['name' => Permissions::INVOICES_INDEX]);

        $this->admin->givePermissionTo($this->permission);
        $this->customer->givePermissionTo($this->permission2);
    }

    public function admin_user_sees_all_payments()
    {
        $adminUser = User::factory()->create();
        $adminUser->assignRole($this->admin);
        $this->actingAs($adminUser);

        $payments = Payment::factory()->count(3)->create();

        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Dashboard')
            ->has('payments', 3)
        );
    }

    public function test_non_admin_user_sees_own_payments()
    {
        $user = User::factory()->create();
        $user->assignRole($this->customer);
        $this->actingAs($user);

        $ownPayments = Payment::factory()->count(2)->create([
            'microsite_id' => Microsite::factory()->create(['user_id' => $user->id])->id,
        ]);

        $otherPayments = Payment::factory()->count(2)->create();

        $response = $this->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Dashboard')
            ->has('payments', 2)
        );
    }
}
