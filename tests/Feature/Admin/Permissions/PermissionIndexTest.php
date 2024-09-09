<?php

namespace Tests\Feature\Admin\Permissions;

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

class PermissionIndexTest extends TestCase
{
    use HasPermissions;
    use RefreshDatabase;

    private const RESOURCE_NAME = 'permissions.index';

    private string $route;

    private Role $admin;

    private Permission $permission;

    protected function setUp(): void
    {
        parent::setUp();

        $this->route = route(self::RESOURCE_NAME);

        $this->admin = Role::create(['name' => Roles::ADMIN]);
        $this->permission = Permission::create(['name' => Permissions::PERMISSIONS_INDEX]);

        $this->admin->givePermissionTo($this->permission);
    }

    #[Test]
    public function guest_user_can_not_access_to_permission_list(): void
    {
        $response = $this->get($this->route);

        $response->assertRedirect('login');
    }

    #[Test]
    public function unauthorized_user_cant_access_to_permission_list(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get($this->route);

        $response->assertForbidden();
    }

    #[Test]
    public function authorized_user_can_access_to_permission_list(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $user->assignRole($this->admin);

        /** @var \App\Models\Role $role */
        $permission = Permission::create(['name' => 'custom.permission']);

        $response = $this->actingAs($user)
            ->get($this->route);

        $response->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Permissions/Index')
            )
            ->assertSee($permission->name);
    }
}
