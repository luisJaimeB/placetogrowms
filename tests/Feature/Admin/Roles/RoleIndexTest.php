<?php

namespace Tests\Feature\Admin\Roles;

use App\Constants\Permissions;
use App\Constants\Roles;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;
use Tests\TestCase;

class RoleIndexTest extends TestCase
{
    use HasPermissions;
    use RefreshDatabase;

    private const RESOURCE_NAME = 'roles.index';
    private string $route;
    private Role $admin;
    private Permission $permission;

    protected function setUp(): void
    {
        parent::setUp();

        $this->route = route(self::RESOURCE_NAME);
        
        $this->admin = Role::create(['name' => Roles::ADMIN]);
        $this->permission = Permission::create(['name' => Permissions::ROLES_INDEX]);
        
        $this->admin->givePermissionTo($this->permission);
    }

    #[Test]
    public function guest_user_can_not_access_to_role_list(): void
    {
        $response = $this->get($this->route);

        $response->assertRedirect('login');
    }

    #[Test]
    public function unauthorized_user_can_not_access_to_role_list(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get($this->route);

        $response->assertForbidden();
    }

    #[Test]
    public function authorized_user_can_access_to_role_list(): void
    {

        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $user->assignRole($this->admin);

        /** @var \App\Models\Role $role */
        $role = Role::create(['name' => 'custom']);

        $response = $this->actingAs($user)
            ->get($this->route);

        $response->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Roles/Index')
                ->where('roles.current_page', 1) 
                ->where('roles.total', 2) 
                ->where('roles.per_page', 25) 
                ->where('roles.first_page_url', 'http://localhost/admin/roles?page=1') 
                ->where('roles.last_page_url', 'http://localhost/admin/roles?page=1') 
                ->where('roles.prev_page_url', null) 
                ->where('roles.next_page_url', null)
                ->has('roles.links', 3)
            )
            ->assertSee($role->name);
    }
}
