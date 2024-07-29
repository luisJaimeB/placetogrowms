<?php

namespace Tests\Feature\Admin;

use App\Constants\Permissions;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;
use Tests\TestCase;

class UserEditTest extends TestCase
{
    use HasPermissions;
    use RefreshDatabase;

    private const RESOURCE_NAME = 'users.edit';
    private string $route;
    private User $customer;
    private Role $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = User::factory()->create();
        $this->route = route(self::RESOURCE_NAME, $this->customer->id);

        $this->admin = Role::create(['name' => 'Admin']);
        $updatePermission = Permission::create(['name' => Permissions::USERS_UPDATE]);

        $this->admin->givePermissionTo($updatePermission);
    }

    #[Test]
    public function guest_user_cant_edit_an_user(): void
    {
        $response = $this->get($this->route);

        $response->assertRedirect('login');
    }

    #[Test]
    public function unauthorized_user_can_not_edit_an_user(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get($this->route);

        $response->assertForbidden();
    }

    #[Test]
    public function authorized_user_can_edit_an_user(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $user->assignRole($this->admin);

        $response = $this->actingAs($user)
            ->get($this->route);

        $response->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Users/Edit')
                ->has('userToEdit', fn (Assert $page) => $page
                    ->where('id', $this->customer->id)
                    ->where('name', $this->customer->name)
                    ->where('email', $this->customer->email)
                    ->where('email_verified_at', $this->customer->email_verified_at ? $this->customer->email_verified_at->toISOString() : null)
                    ->where('created_at', $this->customer->created_at->toISOString())
                    ->where('updated_at', $this->customer->updated_at->toISOString())
                    ->where('permissions', $this->customer->permissions->toArray())
                    ->where('roles', $this->customer->roles->toArray())
                )
                ->has('userPermissions')
                ->has('user.roles')
            );


    }
}
