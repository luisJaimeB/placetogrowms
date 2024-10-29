<?php

namespace Tests\Feature\Suscriptor;

use App\Constants\Permissions;
use App\Constants\Roles;
use App\Models\Suscription;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;
use Tests\TestCase;

class IndexSuscriptionSuscriptorTest extends TestCase
{
    use HasPermissions;
    use RefreshDatabase;

    private const RESOURCE_NAME = 'subscriptions.index';

    private string $route;

    private Role $admin;

    private User $user;

    private Permission $permission;

    protected function setUp(): void
    {
        parent::setUp();

        $this->route = route(self::RESOURCE_NAME);

        $this->user = User::factory()->create();
        $this->admin = Role::create(['name' => Roles::SUBSCRIBER]);
        $this->permission = Permission::create(['name' => Permissions::SUBSCRIPTIONS_INDEX]);

        $this->admin->givePermissionTo($this->permission);
    }

    #[Test]
    public function guest_user_can_not_access_to_suscription_list(): void
    {
        $response = $this->get($this->route);

        $response->assertRedirect('login');
    }

    #[Test]
    public function unauthorized_user_cant_access_to_suscription_list(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get($this->route);

        $response->assertForbidden();
    }

    public function index_displays_suscriptions_for_authenticated_user()
    {
        $suscription = Suscription::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)
            ->get($this->route);

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Suscriptions/Index')
            ->has('suscriptions', 1)
        );
    }
}
