<?php

namespace Tests\Feature\Microsites;

use App\Constants\Permissions;
use App\Models\Microsite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;
use Tests\TestCase;

class MicrositeEditTest extends TestCase
{
    use HasPermissions;
    use RefreshDatabase;

    private const RESOURCE_NAME = 'microsites.edit';
    private string $route;
    private Microsite $microsite;
    private Role $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->microsite = Microsite::factory()->create();
        $this->route = route(self::RESOURCE_NAME, $this->microsite);
        
        $this->admin = Role::create(['name' => 'Admin']);
        $updatePermission = Permission::create(['name' => Permissions::MICROSITES_UPDATE]);
        
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
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get($this->route);

        $response->assertForbidden();
    }

    #[Test]
    public function authorized_user_can_edit_an_user(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $user->assignRole($this->admin);

        $response = $this->actingAs($user)
            ->get($this->route);

        $response->assertOk()
        ->assertInertia(fn (Assert $page) => $page
            ->component('Microsites/Edit')
            ->where('microsite.id', $this->microsite->id)
            ->where('microsite.name', $this->microsite->name)
            
        );
    }
}
