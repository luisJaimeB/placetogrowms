<?php

namespace Tests\Feature\Microsites;

use App\Constants\Permissions;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;
use Tests\TestCase;

class MicrositeCreateTest extends TestCase
{
    use HasPermissions;
    use RefreshDatabase;

    private const RESOURCE_NAME = 'microsites.create';

    private string $route;

    protected function setUp(): void
    {
        parent::setUp();

        $this->route = route(self::RESOURCE_NAME);

        $adminRole = Role::create(['name' => 'Admin']);
        $createPermission = Permission::create(['name' => Permissions::MICROSITES_CREATE]);

        $adminRole->givePermissionTo($createPermission);
    }

    #[Test]
    public function test_guest_user_cant_access_to_microsite_creation_form(): void
    {
        $response = $this->get($this->route);
        $response->assertRedirect('login');
    }

    #[Test]
    public function test_unauthorized_user_can_not_access_to_microsite_creation_form(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get($this->route);

        $response->assertForbidden();
    }

    #[Test]
    public function authorized_user_can_access_to_microsite_creation_form(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $user->assignRole('Admin');

        $response = $this->actingAs($user)
            ->get($this->route);

        $response->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Microsites/Create'));
    }
}
