<?php

namespace Tests\Feature\Invoices;

use App\Constants\InvoicesStatus;
use App\Constants\Permissions;
use App\Constants\Roles;
use App\Constants\SurchargeRate;
use App\Constants\TypesSites;
use App\Models\BuyerIdType;
use App\Models\Currency;
use App\Models\Microsite;
use App\Models\TypeSite;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;
use Tests\TestCase;

class StoreInvoiceTest extends TestCase
{
    use HasPermissions;
    use RefreshDatabase;
    use WithFaker;

    private const RESOURCE_NAME = 'invoices.store';

    private string $route;

    private Role $adminrole;

    private Permission $permission;

    private User $user;

    private Microsite $microsite;

    private TypeSite $typeSite;

    private BuyerIdType $buyerIdType;

    private Currency $currency;

    protected function setUp(): void
    {
        parent::setUp();

        $this->route = route(self::RESOURCE_NAME);

        $this->siteType = TypeSite::create(['name' => TypesSites::SITE_TYPE_INVOICE->value]);
        $this->microsite = Microsite::factory()->withTypeSiteId($this->siteType->id)->create();
        $this->buyerIdType = BuyerIdType::factory()->create();
        $this->currency = Currency::factory()->create();
        $this->user = User::factory()->create();
        $this->adminrole = Role::create(['name' => Roles::ADMIN]);
        $this->permission = Permission::create(['name' => Permissions::INVOICES_CREATE]);

        $this->adminrole->givePermissionTo($this->permission);
        $this->user->assignRole($this->adminrole);
    }

    public function test_guest_user_can_not_access_to_invoices_store(): void
    {
        $response = $this->post($this->route);

        $response->assertRedirect('login');
    }

    public function test_unauthorized_user_cant_access_to_invoices_store(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post($this->route);

        $response->assertForbidden();
    }

    public function test_authorized_user_can_store_a_invoice(): void
    {
        Carbon::setTestNow(now());
        $expirationDate = $this->faker->dateTimeBetween('+30 days', '+60 days');
        $maxSurchargeDate = (clone $expirationDate)->modify('-1 day');

        $surchargeDate = $this->faker->dateTimeBetween('now', $maxSurchargeDate->format('Y-m-d'))->format('Y-m-d');

        $data = [
            'status' => InvoicesStatus::paid->value,
            'order_number' => Str::random(32),
            'debtor_name' => $this->faker->words(2, true),
            'microsite_id' => $this->microsite->id,
            'identification_type_id' => $this->buyerIdType->id,
            'identification_number' => strval($this->faker->randomFloat(0, 0, 9999999999.99)),
            'email' => 'luisyi1998@gmail.com',
            'description' => $this->faker->sentence(10),
            'amount' => $this->faker->randomFloat(2, 0, 9999999999.99),
            'currency_id' => $this->currency->id,
            'expiration_date' => $expirationDate->format('Y-m-d'),
            'user_id' => $this->user->id,
            'payment_id' => null,
            'surcharge_date' => $surchargeDate,
            'surcharge_rate' => $this->faker->randomElement(SurchargeRate::toArray()),
            'percent' => $this->faker->optional()->numberBetween(0, 100),
            'additional_amount' => $this->faker->optional()->numberBetween(1000, 10000000),
        ];

        //dd($data);
        $response = $this->actingAs($this->user)
            ->post($this->route, $data);

        $response->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('invoices.index'));

        $this->assertDatabaseHas('invoices', [
            'order_number' => $data['order_number'],
            'debtor_name' => $data['debtor_name'],
        ]);
    }
}
