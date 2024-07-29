<?php

namespace Tests\Feature\Admin\Permissions;

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

class PermissionStoreTest extends TestCase
{
    use HasPermissions;
    use RefreshDatabase;
    use WithFaker;

    private const RESOURCE_NAME = 'permissions.store';

    private string $route;

    private Role $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->route = route(self::RESOURCE_NAME);

        $this->admin = Role::create(['name' => Roles::ADMIN]);
        $updatePermission = Permission::create(['name' => Permissions::PERMISSIONS_CREATE]);

        $this->admin->givePermissionTo($updatePermission);
    }

    #[Test]
    public function guest_user_can_not_store_an_permission(): void
    {
        $response = $this->post($this->route);

        $response->assertRedirect('login');
    }

    #[Test]
    public function unauthorized_user_cant_store_an_permission(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post($this->route);

        $response->assertForbidden();
    }

    #[Test]
    public function authorized_user_can_store_an_permission(): void
    {
        Carbon::setTestNow(now());

        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $user->assignRole($this->admin);

        $data = [
            'name' => $this->faker->name,
        ];

        $response = $this->actingAs($user)
            ->post($this->route, $data);

        $response->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('permissions.index'));

        $this->assertDatabaseHas('permissions', [
            'name' => $data['name'],
        ]);
    }
}
