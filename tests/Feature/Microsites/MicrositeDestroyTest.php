<?php

namespace Tests\Feature\Microsites;

use App\Constants\Permissions;
use App\Models\Microsite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;
use Tests\TestCase;

class MicrositeDestroyTest extends TestCase
{
    use HasPermissions;
    use RefreshDatabase;

    private const RESOURCE_NAME = 'microsites.destroy';
    private string $route;

    private Microsite $microsite;

    protected function setUp(): void
    {
        parent::setUp();

        $this->microsite = Microsite::factory()->create();
        $this->route = route(self::RESOURCE_NAME, $this->microsite);
        
        $adminRole = Role::create(['name' => 'Admin']);
        $deletePermission = Permission::create(['name' => Permissions::MICROSITES_DELETE]);
        
        $adminRole->givePermissionTo($deletePermission);

    }


    public function guest_user_cant_delete_an_user(): void
    {
        $response = $this->delete($this->route);

        $response->assertRedirect('login');
    }

 
    public function unauthorized_user_can_not_delete_an_user(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->delete($this->route);

        $response->assertForbidden();
    }


    public function authorized_user_can_delete_an_user(): void
    {

        /** @var \App\Models\User $user */
        $user = User::factory()->create();
        $user->assignRole('Admin');

        $response = $this->actingAs($user)
            ->delete($this->route);

        $response->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('microsites.index'));

        $this->assertDatabaseMissing('microsites', [
            'id' => $this->microsite->id,
        ]);
    }

}
