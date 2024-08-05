<?php

namespace Tests\Feature\Admin\Roles;

use App\Constants\Permissions;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;
use Tests\TestCase;

class RoleEditTest extends TestCase
{
    use HasPermissions;
    use RefreshDatabase;

    private const RESOURCE_NAME = 'roles.edit';

    private string $route;

    private User $customer;

    private Role $admin;

    private Role $role;

    protected function setUp(): void
    {
        parent::setUp();

        $this->role = Role::create(['name' => 'roleTest']);
        $this->route = route(self::RESOURCE_NAME, $this->role);

        $this->admin = Role::create(['name' => 'Admin']);
        $updatePermission = Permission::create(['name' => Permissions::ROLES_UPDATE]);

        $this->admin->givePermissionTo($updatePermission);
    }

    #[Test]
    public function guest_user_cant_edit_an_role(): void
    {
        $response = $this->get($this->route);

        $response->assertRedirect('login');
    }

    #[Test]
    public function unauthorized_user_can_not_edit_an_role(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get($this->route);

        $response->assertForbidden();
    }

    #[Test]
    public function authorized_user_can_edit_an_role(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $user->assignRole($this->admin);

        /** @var \App\Models\Role $role */
        $roless = Role::create(['name' => 'custom']);

        $response = $this->actingAs($user)
            ->get($this->route);

        $response->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Roles/Edit')
                ->where('role.id', $this->role->id)
                ->where('role.name', $this->role->name)
                ->has('rolePermissions')
                ->has('user.permissions')
                ->has('user.roles')
            );
    }
}
