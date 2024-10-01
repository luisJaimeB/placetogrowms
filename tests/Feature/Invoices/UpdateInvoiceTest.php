<?php

namespace Tests\Feature\Invoices;

use App\Constants\InvoicesStatus;
use App\Constants\Permissions;
use App\Constants\Roles;
use App\Models\BuyerIdType;
use App\Models\Currency;
use App\Models\Invoice;
use App\Models\Microsite;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;
use Tests\TestCase;

class UpdateInvoiceTest extends TestCase
{
    use HasPermissions;
    use RefreshDatabase;
    use WithFaker;

    private const RESOURCE_NAME = 'invoices.update';

    private string $route;

    private Role $adminrole;

    private Permission $permission;

    private User $user;

    private Microsite $microsite;

    private BuyerIdType $buyerIdType;

    private Currency $currency;

    private Invoice $invoice;

    protected function setUp(): void
    {
        parent::setUp();

        $this->currency = Currency::factory()->create();
        $this->invoice = Invoice::factory()->create();
        $this->route = route(self::RESOURCE_NAME, $this->invoice);

        $this->microsite = Microsite::factory()->create();
        $this->buyerIdType = BuyerIdType::factory()->create();
        $this->user = User::factory()->create();
        $this->adminrole = Role::create(['name' => Roles::ADMIN]);
        $this->permission = Permission::create(['name' => Permissions::INVOICES_UPDATE]);

        $this->adminrole->givePermissionTo($this->permission);
        $this->user->assignRole($this->adminrole);
    }

    public function test_guest_user_can_not_access_to_invoices_update(): void
    {
        $response = $this->patch($this->route);

        $response->assertRedirect('login');
    }

    public function test_unauthorized_user_cant_access_to_invoices_update(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->patch($this->route);

        $response->assertForbidden();
    }

    public function test_authorized_user_can_update_a_invoice(): void
    {
        Carbon::setTestNow(now());

        $data = [
            'status' => InvoicesStatus::paid->value,
            'order_number' => Str::random(32),
            'debtor_name' => $this->faker->word.' '.$this->faker->word,
            'microsite_id' => $this->microsite->id,
            'identification_type_id' => $this->buyerIdType->id,
            'identification_number' => strval($this->faker->randomFloat(0, 0, 9999999999.99)),
            'email' => 'luisyi1998@gmail.com',
            'description' => $this->faker->sentence(10),
            'amount' => $this->faker->randomFloat(2, 0, 9999999999.99),
            'currency_id' => $this->currency->id,
            'expiration_date' => $this->faker->dateTimeBetween('+1 year', '+2 years')->format('Y-m-d'),
            'user_id' => $this->user->id,
            'payment_id' => null,
        ];

        $response = $this->actingAs($this->user)
            ->patch($this->route, $data);

        $response->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('invoices.index'));

        $this->assertDatabaseHas('invoices', [
            'order_number' => $data['order_number'],
            'debtor_name' => $data['debtor_name'],
        ]);
    }
}
