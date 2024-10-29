<?php

namespace Tests\Feature\Admin\Permissions;

use App\Constants\Permissions;
use App\Constants\Roles;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;
use Tests\TestCase;

class PermissionDestroyTest extends TestCase
{
    use HasPermissions;
    use RefreshDatabase;

    private const RESOURCE_NAME = 'permissions.destroy';

    private string $route;

    private Role $admin;

    private Permission $permission;

    private User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Role::create(['name' => Roles::ADMIN]);
        $this->permission = Permission::create(['name' => Permissions::PERMISSIONS_DELETE]);

        $this->customer = User::factory()->create();
        $this->route = route(self::RESOURCE_NAME, $this->permission);

        $this->admin->givePermissionTo($this->permission);
    }

    #[Test]
    public function guest_user_cant_delete_an_permission(): void
    {
        $response = $this->delete($this->route);

        $response->assertRedirect('login');
    }

    #[Test]
    public function unauthorized_user_can_not_delete_an_permission(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->delete($this->route);

        $response->assertForbidden();
    }

    #[Test]
    public function authorized_user_can_delete_an_permission(): void
    {

        /** @var User $user */
        $user = User::factory()->create();
        $user->assignRole($this->admin);

        $response = $this->actingAs($user)
            ->delete($this->route);

        $response->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('permissions.index'));

        $this->assertDatabaseMissing('permissions', [
            'id' => $this->permission->id,
        ]);
    }
}
