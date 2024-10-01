<?php

namespace Tests\Feature\Plans;

use App\Constants\Permissions;
use App\Constants\Roles;
use App\Models\SuscriptionPlan;
use App\Models\User;
use Database\Seeders\CurrencySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;
use Tests\TestCase;

class IndexPlanTest extends TestCase
{
    use HasPermissions;
    use RefreshDatabase;

    private const RESOURCE_NAME = 'planes.index';

    private string $route;

    private Role $adminrole;

    private Permission $permission;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(CurrencySeeder::class);

        $this->route = route(self::RESOURCE_NAME);

        $this->user = User::factory()->create();
        $this->adminrole = Role::create(['name' => Roles::ADMIN]);
        $this->permission = Permission::create(['name' => Permissions::PLANES_INDEX]);

        $this->adminrole->givePermissionTo($this->permission);
        $this->user->assignRole($this->adminrole);
    }

    public function test_guest_user_can_not_access_to_planes_list(): void
    {
        $response = $this->get($this->route);

        $response->assertRedirect('login');
    }

    public function test_unauthorized_user_cant_access_to_planes_list(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get($this->route);

        $response->assertForbidden();
    }

    public function test_authorized_user_can_displays_all_plans(): void
    {
        $plans = SuscriptionPlan::factory()->count(3)->create();

        $response = $this->actingAs($this->user)
            ->get($this->route);

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('SuscriptionPlanes/Index')
            ->has('plans', 3)
        );
    }
}
