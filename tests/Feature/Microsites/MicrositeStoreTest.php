<?php

namespace Tests\Feature\Microsites;

use App\Actions\CreateMicrositeAction;
use App\Constants\Permissions;
use App\Constants\Roles;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Microsite;
use App\Models\TypeSite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;
use Tests\TestCase;

class MicrositeStoreTest extends TestCase
{
    use HasPermissions;
    use RefreshDatabase;
    use WithFaker;

    private const RESOURCE_NAME = 'microsites.store';

    private string $route;

    private Role $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->route = route(self::RESOURCE_NAME);

        $this->admin = Role::create(['name' => Roles::ADMIN->value]);
        $updatePermission = Permission::create(['name' => Permissions::MICROSITES_CREATE->value]);

        $this->admin->givePermissionTo($updatePermission);
    }

    #[Test]
    public function guest_user_can_not_store_an_microsite(): void
    {
        $response = $this->post($this->route);

        $response->assertRedirect('login');
    }

    #[Test]
    public function unauthorized_user_cant_store_an_microste(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post($this->route);

        $response->assertForbidden();
    }

    #[Test]
    public function authorized_user_can_store_an_microsite(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $user->assignRole('Admin');
        $siteType = TypeSite::factory()->create();
        $category = Category::factory()->create();
        $currency = Currency::factory()->create();

        $data = [
            'name' => fake()->name(),
            'type_site_id' => $siteType->id,
            'category_id' => $category->id,
            'expiration' => fake()->numberBetween(1, 100),
            'currency' => [$currency->id],
        ];

        $route = route('microsites.store');

        $response = $this->actingAs($user)->post($route, $data);

        $response->assertStatus(302);
        $response->assertSessionDoesntHaveErrors();

        $microsite = Microsite::where('name', $data['name'])->first();

        $this->assertNotNull($microsite, 'Microsite was not created');

        $response->assertRedirect(route('microsites.show', ['microsite' => $microsite->id]));

        $this->assertDatabaseHas('microsites', [
            'name' => $data['name'],
            'type_site_id' => $siteType->id,
            'category_id' => $category->id,
            'expiration' => $data['expiration'],
            'user_id' => $user->id,
        ]);

        $this->assertDatabaseHas('currency_microsite', [
            'microsite_id' => $microsite->id,
            'currency_id' => $currency->id,
        ]);
    }

    public function store_returns_back_with_error_when_microsite_is_null(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $user->assignRole('Admin');

        $data = [
            'name' => fake()->name(),
            'type_site_id' => 1,
            'category_id' => 1,
            'expiration' => fake()->numberBetween(1, 100),
        ];

        $mock = Mockery::mock(CreateMicrositeAction::class);
        $mock->shouldReceive('execute')->andReturn(false);

        $this->app->instance(CreateMicrositeAction::class, $mock);

        $response = $this->actingAs($user)->post($this->route, $data);
        $response->assertRedirect();
        $response->assertSessionHas('error', 'Microsite could not be created.');
    }
}
