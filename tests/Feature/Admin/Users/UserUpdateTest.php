<?php

namespace Tests\Feature\Admin;

use App\Constants\Permissions;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;
use Tests\TestCase;

class UserUpdateTest extends TestCase
{
    use HasPermissions;
    use RefreshDatabase;
    use WithFaker;

    private const RESOURCE_NAME = 'users.update';
    private string $route;
    private User $customer;
    private Role $admin;
    private Permission $permission;

    protected function setUp(): void
    {
        parent::setUp();

        $this->customer = User::factory()->create();
        $this->route = route(self::RESOURCE_NAME, $this->customer);

        $this->admin = Role::create(['name' => 'Admin']);
        $this->permission = Permission::create(['name' => Permissions::USERS_UPDATE]);

        $this->admin->givePermissionTo($this->permission);
    }

    #[Test]
    public function guest_user_can_not_update_an_user(): void
    {
        $response = $this->patch($this->route);

        $response->assertRedirect('login');
    }

    #[Test]
    public function unauthorized_user_cant_update_an_user(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->patch($this->route);

        $response->assertForbidden();
    }

    #[Test]
    public function authorized_user_can_update_an_user(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $user->assignRole($this->admin);

        $data = [
            'name' => $this->faker->name,
            'email' => $this->faker->freeEmail(),
            'password' => 'password',
            'password_confirmation' => 'password',
            'rol' => $this->admin->id
        ];

        $response = $this->actingAs($user)
            ->patch($this->route, $data);

        $response->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('users.index'));

        $this->assertDatabaseHas('users', [
            'name' => $data['name'],
            'email' => $data['email'],
            'email_verified_at' => now(),
        ]);
    }
}
