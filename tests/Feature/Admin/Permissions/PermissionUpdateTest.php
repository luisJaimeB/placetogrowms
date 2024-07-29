<?php

namespace Tests\Feature\Admin\Permissions;

use App\Constants\Permissions;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;
use Tests\TestCase;

class PermissionUpdateTest extends TestCase
{
    use HasPermissions;
    use RefreshDatabase;
    use WithFaker;

    private const RESOURCE_NAME = 'permissions.update';

    private string $route;

    private Role $admin;

    private Permission $permission;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Role::create(['name' => 'Admin']);
        $this->permission = Permission::create(['name' => Permissions::PERMISSIONS_UPDATE]);

        $this->admin->givePermissionTo($this->permission);
        $this->route = route(self::RESOURCE_NAME, $this->permission);
    }

    #[Test]
    public function guest_user_can_not_update_an_permission(): void
    {
        $response = $this->patch($this->route);

        $response->assertRedirect('login');
    }

    #[Test]
    public function unauthorized_user_cant_update_an_permission(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->patch($this->route);

        $response->assertForbidden();
    }

    #[Test]
    public function authorized_user_can_update_an_permission(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $user->assignRole($this->admin);

        $data = [
            'name' => $this->faker->name,
        ];

        $response = $this->actingAs($user)
            ->patch($this->route, $data);

        $response->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('permissions.index'));

        $this->assertDatabaseHas('permissions', [
            'name' => $data['name'],
        ]);
    }
}
