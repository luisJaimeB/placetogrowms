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

class DeleteInvoiceTest extends TestCase
{
    use HasPermissions;
    use RefreshDatabase;

    private const RESOURCE_NAME = 'invoices.destroy';
    private string $route;
    private Role $adminrole;
    private Permission $permission;
    private User $user;
    private Invoice $invoice;

    protected function setUp(): void
    {
        parent::setUp();

        Currency::factory()->create();
        $siteType = TypeSite::create(['name' => TypesSites::SITE_TYPE_INVOICE->value]);
        $microsite = Microsite::factory()->withTypeSiteId($siteType->id)->create();
        $this->invoice = Invoice::factory()->withMicrositeId($microsite->id)->create();

        $this->route = route(self::RESOURCE_NAME, $this->invoice);

        $this->user = User::factory()->create();
        $this->adminrole = Role::create(['name' => Roles::ADMIN->value]);
        $this->permission = Permission::create(['name' => Permissions::INVOICES_DELETE->value]);

        $this->adminrole->givePermissionTo($this->permission);
        $this->user->assignRole($this->adminrole);
    }

    public function test_guest_user_can_not_access_to_invoices_delete(): void
    {
        $response = $this->delete($this->route);

        $response->assertRedirect('login');
    }

    public function test_unauthorized_user_cant_access_to_invoices_delete(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)
            ->delete($this->route);

        $response->assertForbidden();
    }

    public function test_authorized_user_can_delete_a_invoice(): void
    {
        $response = $this->actingAs($this->user)
            ->delete($this->route);

        $response->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('invoices.index'));

        $this->assertDatabaseMissing('invoices', [
            'id' => $this->invoice->id,
        ]);
    }
}
