<?php

namespace Tests\Feature\Admin\Roles;

use App\Constants\Permissions;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;
use Tests\TestCase;

class RoleUpdateTest extends TestCase
{
    use HasPermissions;
    use RefreshDatabase;
    use WithFaker;

    private const RESOURCE_NAME = 'roles.update';
    private string $route;
    private User $customer;
    private Role $admin;
    private Role $roleP;
    private Permission $permission1;
    private Permission $permission2;
    private Permission $permission3;

    protected function setUp(): void
    {
        parent::setUp();

        $this->role = Role::create(['name' => 'roleTest']);
        $this->route = route(self::RESOURCE_NAME, $this->role);

        $this->admin = Role::create(['name' => 'Admin']);
        $this->roleP = Role::create(['name' => 'rolePrueba']);
        $this->permission1 = Permission::create(['name' => Permissions::ROLES_CREATE]);
        $this->permission2 = Permission::create(['name' => Permissions::ROLES_INDEX]);
        $this->permission3 = Permission::create(['name' => Permissions::ROLES_UPDATE]);

        $this->admin->givePermissionTo($this->permission1);
        $this->admin->givePermissionTo($this->permission2);
        $this->admin->givePermissionTo($this->permission3);
    }

    #[Test]
    public function guest_user_can_not_update_an_role(): void
    {
        $response = $this->patch($this->route);

        $response->assertRedirect('login');
    }

    #[Test]
    public function unauthorized_user_cant_update_an_role(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->patch($this->route);

        $response->assertForbidden();
    }

    #[Test]
    public function authorized_user_can_update_an_role(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $user->assignRole($this->admin);

        $data = [
            'name' => $this->faker->name,
            'permissions' => [
                $this->permission1->id,
                $this->permission2->id,
                $this->permission3->id,
                ]
        ];

        $response = $this->actingAs($user)
            ->patch($this->route, $data);

        $response->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('roles.index'));

        $this->assertDatabaseHas('roles', [
            'name' => $data['name'],
        ]);
}
}
