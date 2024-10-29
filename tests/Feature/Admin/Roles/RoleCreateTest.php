<?php

namespace Tests\Feature\Admin\Roles;

use App\Constants\Permissions;
use App\Constants\Roles;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;
use Tests\TestCase;

class RoleCreateTest extends TestCase
{
    use HasPermissions;
    use RefreshDatabase;

    private const RESOURCE_NAME = 'roles.create';

    private string $route;

    private Role $admin;

    private Permission $permission;

    protected function setUp(): void
    {
        parent::setUp();

        $this->route = route(self::RESOURCE_NAME);

        $this->admin = Role::create(['name' => Roles::ADMIN]);
        $this->permission = Permission::create(['name' => Permissions::ROLES_CREATE]);

        $this->admin->givePermissionTo($this->permission);
    }

    #[Test]
    public function test_guest_user_cant_access_to_role_creation_form(): void
    {
        $response = $this->get($this->route);
        $response->assertRedirect('login');
    }

    #[Test]
    public function test_unauthorized_user_can_not_access_to_role_creation_form(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get($this->route);

        $response->assertForbidden();
    }

    #[Test]
    public function authorized_user_can_access_to_role_creation_form(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $user->assignRole($this->admin);

        $response = $this->actingAs($user)
            ->get($this->route);

        $response->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Roles/Create'));
    }
}
