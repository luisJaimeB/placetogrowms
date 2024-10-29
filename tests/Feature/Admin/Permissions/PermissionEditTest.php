<?php

namespace Tests\Feature\Admin\Permissions;

use App\Constants\Permissions;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;
use Tests\TestCase;

class PermissionEditTest extends TestCase
{
    use HasPermissions;
    use RefreshDatabase;

    private const RESOURCE_NAME = 'permissions.edit';

    private string $route;

    private User $customer;

    private Role $admin;

    private Permission $permission;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Role::create(['name' => 'Admin']);
        $this->permission = Permission::create(['name' => Permissions::PERMISSIONS_UPDATE->value]);

        $this->admin->givePermissionTo($this->permission);
        $this->route = route(self::RESOURCE_NAME, $this->permission);
    }

    #[Test]
    public function guest_user_cant_edit_an_permission(): void
    {
        $response = $this->get($this->route);

        $response->assertRedirect('login');
    }

    #[Test]
    public function unauthorized_user_cant_edit_an_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get($this->route);

        $response->assertForbidden();
    }

    #[Test]
    public function authorized_user_can_edit_an_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $user->assignRole($this->admin);

        $response = $this->actingAs($user)
            ->get($this->route);

        $response->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Permissions/Edit')
                ->where('permission.id', $this->permission->id)
                ->where('permission.name', $this->permission->name)
                ->has('user.permissions')
                ->has('user.roles')
            );
    }
}
