<?php

namespace Tests\Feature\Admin\Users;

use App\Constants\Permissions;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;
use Tests\TestCase;

class UserDestroyTest extends TestCase
{
    use HasPermissions;
    use RefreshDatabase;

    private const RESOURCE_NAME = 'users.destroy';

    private string $route;

    private User $customer;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = User::factory()->create();
        $this->route = route(self::RESOURCE_NAME, $this->customer);

        $adminRole = Role::create(['name' => 'Admin']);
        $deletePermission = Permission::create(['name' => Permissions::USERS_DELETE]);

        $adminRole->givePermissionTo($deletePermission);

    }

    #[Test]
    public function guest_user_cant_delete_an_user(): void
    {
        $response = $this->delete($this->route);

        $response->assertRedirect('login');
    }

    #[Test]
    public function unauthorized_user_can_not_delete_an_user(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->delete($this->route);

        $response->assertForbidden();
    }

    #[Test]
    public function authorized_user_can_delete_an_user(): void
    {

        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $user->assignRole('Admin');

        $response = $this->actingAs($user)
            ->delete($this->route);

        $response->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('users.index'));

        $this->assertDatabaseMissing('users', [
            'id' => $this->customer->id,
        ]);
    }
}
