<?php

namespace Tests\Feature\Plans;

use App\Constants\Permissions;
use App\Constants\Roles;
use App\Models\SuscriptionPlan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;
use Tests\TestCase;

class EditPlanTest extends TestCase
{
    use HasPermissions;
    use RefreshDatabase;

    private const RESOURCE_NAME = 'planes.edit';

    private string $route;

    private Role $adminrole;

    private Permission $permission;

    private User $user;

    private SuscriptionPlan $plan;

    protected function setUp(): void
    {
        parent::setUp();

        $this->plan = SuscriptionPlan::factory()->create();

        $this->route = route(self::RESOURCE_NAME, $this->plan);

        $this->user = User::factory()->create();
        $this->adminrole = Role::create(['name' => Roles::ADMIN]);
        $this->permission = Permission::create(['name' => Permissions::PLANES_UPDATE]);

        $this->adminrole->givePermissionTo($this->permission);
        $this->user->assignRole($this->adminrole);
    }

    public function test_guest_user_can_not_access_to_planes_edit(): void
    {
        $response = $this->get($this->route);

        $response->assertRedirect('login');
    }

    public function test_unauthorized_user_cant_access_to_planes_edit(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->get($this->route);

        $response->assertForbidden();
    }

    public function test_authorized_user_can_edit_a_plan(): void
    {
        $response = $this->actingAs($this->user)
            ->get($this->route);

        $response->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('SuscriptionPlanes/Edit')
                ->where('plan.id', $this->plan->id)
                ->where('plan.name', $this->plan->name)
                ->has('plan.interval')
                ->has('plan.items')
            );
    }
}
