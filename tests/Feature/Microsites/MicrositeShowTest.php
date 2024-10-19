<?php

namespace Tests\Feature\Microsites;

use App\Constants\Permissions;
use App\Constants\Roles;
use App\Constants\TypesSites;
use App\Models\Microsite;
use App\Models\TypeSite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Inertia\Testing\AssertableInertia as Assert;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;
use Tests\TestCase;

class MicrositeShowTest extends TestCase
{
    use HasPermissions;
    use RefreshDatabase;

    private const RESOURCE_NAME = 'microsites.show';
    private string $route;
    private Role $admin;
    private TypeSite $typeSite;
    private Microsite $microsite;
    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Role::create(['name' => Roles::ADMIN]);
        $readPermission = Permission::create(['name' => Permissions::MICROSITES_SHOW]);

        $this->admin->givePermissionTo($readPermission);

        $siteType = TypeSite::create(['name' => TypesSites::SITE_TYPE_INVOICE->value]);
        $this->microsite = Microsite::factory()->withTypeSiteId($siteType->id)->create();

        $this->route = route(self::RESOURCE_NAME, $this->microsite);
    }

    #[Test]
    public function guest_user_can_not_access_to_microsite_show_view(): void
    {
        $response = $this->get($this->route);

        $response->assertRedirect('login');
    }

    #[Test]
    public function unauthorized_user_can_not_access_to_microsite_show_view(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get($this->route);

        $response->assertForbidden();
    }

    #[Test]
    public function authorized_user_can_access_to_microsite_show_view(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $user->assignRole($this->admin);

        $response = $this->actingAs($user)
            ->get($this->route);

        $response->assertOk()
            ->assertInertia(fn (Assert $page) => $page
                ->component('Microsites/Show')
                ->where('microsite.id', $this->microsite->id)
                ->where('microsite.name', $this->microsite->name)
            );
    }
}
