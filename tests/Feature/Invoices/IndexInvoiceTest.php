<?php

namespace Tests\Feature\Invoices;

use App\Constants\Permissions;
use App\Constants\Roles;
use App\Constants\TypesSites;
use App\Models\Currency;
use App\Models\Invoice;
use App\Models\Microsite;
use App\Models\TypeSite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;
use Tests\TestCase;

class IndexInvoiceTest extends TestCase
{
    use HasPermissions;
    use RefreshDatabase;

    private const RESOURCE_NAME = 'invoices.index';

    private string $route;

    private Role $adminrole;

    private Permission $permission;

    private User $user;

    private Microsite $microsite;

    private TypeSite $typeSite;

    protected function setUp(): void
    {
        parent::setUp();

        $this->route = route(self::RESOURCE_NAME);
        $this->siteType = TypeSite::create(['name' => TypesSites::SITE_TYPE_INVOICE->value]);
        $this->microsite = Microsite::factory()->withTypeSiteId($this->siteType->id)->create();

        $this->user = User::factory()->create();
        $this->adminrole = Role::create(['name' => Roles::ADMIN]);
        $this->permission = Permission::create(['name' => Permissions::INVOICES_INDEX]);

        $this->adminrole->givePermissionTo($this->permission);
        $this->user->assignRole($this->adminrole);
    }

    public function test_guest_user_can_not_access_to_invoices_list(): void
    {
        $response = $this->get($this->route);

        $response->assertRedirect('login');
    }

    public function test_unauthorized_user_cant_access_to_invoices_list(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get($this->route);

        $response->assertForbidden();
    }

    public function test_authorized_user_can_displays_all_invoices(): void
    {
        Currency::factory()->create();
        Invoice::factory()->count(3)->withMicrositeId($this->microsite->id)->create([
            'user_id' => $this->user->id,
        ]);

        $response = $this->actingAs($this->user)
            ->get($this->route);

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Invoices/Index')
            ->has('invoices', 3)
        );
    }
}
