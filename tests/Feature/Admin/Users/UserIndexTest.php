<?php

namespace Tests\Feature\Admin\Users;

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

class UserIndexTest extends TestCase
{
    use HasPermissions;
    use RefreshDatabase;

    private const RESOURCE_NAME = 'users.index';

    private string $route;

    private Role $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->route = route(self::RESOURCE_NAME);

        $this->admin = Role::create(['name' => Roles::ADMIN->value]);
        $readPermission = Permission::create(['name' => Permissions::USERS_INDEX->value]);

        $this->admin->givePermissionTo($readPermission);
    }

    #[Test]
    public function guest_user_can_not_access_to_user_list(): void
    {
        $response = $this->get($this->route);

        $response->assertRedirect('login');
    }

    #[Test]
    public function unauthorized_user_can_not_access_to_user_list(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get($this->route);

        $response->assertForbidden();
    }

    #[Test]
    public function authorized_user_can_access_to_user_list(): void
    {

        /** @var User $user */
        $user = User::factory()->create();
        $user->assignRole($this->admin);

        /** @var User $customer */
        $customer = User::factory()->create();

        $response = $this->actingAs($user)
            ->get($this->route);

        $response->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Admin/Users/Index')
                ->where('users.current_page', 1)
                ->where('users.per_page', 3)
                ->where('users.first_page_url', 'http://localhost/admin/users?page=1')
                ->where('users.last_page_url', 'http://localhost/admin/users?page=1')
                ->where('users.prev_page_url', null)
                ->where('users.next_page_url', null)
                ->has('users.links', 3)
            )
            ->assertSee($customer->name);
    }
}
