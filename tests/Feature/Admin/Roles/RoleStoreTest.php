<?php

namespace Tests\Feature\Admin\Roles;

use App\Constants\Permissions;
use App\Constants\Roles;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;
use Tests\TestCase;

class RoleStoreTest extends TestCase
{
    use HasPermissions;
    use RefreshDatabase;
    use WithFaker;

    private const RESOURCE_NAME = 'roles.store';
    private string $route;
    private Role $admin;
    private Permission $permission1;
    private Permission $permission2;
    private Permission $permission3;

    protected function setUp(): void
    {
        parent::setUp();

        $this->route = route(self::RESOURCE_NAME);
        
        $this->admin = Role::create(['name' => Roles::ADMIN]);
        $this->permission1 = Permission::create(['name' => Permissions::ROLES_CREATE]);
        $this->permission2 = Permission::create(['name' => Permissions::ROLES_INDEX]);
        $this->permission3 = Permission::create(['name' => Permissions::ROLES_UPDATE]);
        
        $this->admin->givePermissionTo($this->permission1);
        $this->admin->givePermissionTo($this->permission2);
        $this->admin->givePermissionTo($this->permission3);
    }

    #[Test]
    public function guest_user_can_not_store_an_role(): void
    {
        $response = $this->post($this->route);

        $response->assertRedirect('login');
    }

    #[Test]
    public function unauthorized_user_cant_store_an_role(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post($this->route);

        $response->assertForbidden();
    }

    #[Test]
    public function authorized_user_can_store_an_role(): void
    {
        Carbon::setTestNow(now());

        /** @var \App\Models\User $user */
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
            ->post($this->route, $data);

        $response->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('roles.index'));

        $this->assertDatabaseHas('roles', [
            'name' => $data['name'],
        ]);
    }
}
