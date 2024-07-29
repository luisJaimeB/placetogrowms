<?php

namespace Tests\Feature\Microsites;

use App\Constants\Permissions;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Microsite;
use App\Models\TypeSite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use PHPUnit\Framework\Attributes\Test;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;
use Tests\TestCase;

class MicrositeUpdateTest extends TestCase
{
    use HasPermissions;
    use RefreshDatabase;
    use WithFaker;

    private const RESOURCE_NAME = 'microsites.update';
    private string $route;
    private Role $admin;
    private Permission $permission;
    private Microsite $microsite;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = Role::create(['name' => 'Admin']);
        $this->permission = Permission::create(['name' => Permissions::MICROSITES_UPDATE]);
        $this->microsite = Microsite::factory()->create();

        $this->admin->givePermissionTo($this->permission);
        $this->route = route(self::RESOURCE_NAME, $this->microsite);
    }

    #[Test]
    public function guest_user_can_not_update_an_microsite(): void
    {
        $response = $this->patch($this->route);

        $response->assertRedirect('login');
    }

    #[Test]
    public function unauthorized_user_cant_update_an_microsite(): void
    {
        /** @var User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->patch($this->route);

        $response->assertForbidden();
    }

    #[Test]
    public function authorized_user_can_update_an_microsite(): void
    {
        /** @var User $user */
        $user = User::factory()->create();
        $user->assignRole($this->admin);
        $siteType = TypeSite::factory()->create();
        $category = Category::factory()->create();
        $currency = Currency::factory()->create();

        $updatedData = [
            'name' => fake()->name(),
            'type_site_id' => $siteType->id,
            'category_id' => $category->id,
            'expiration' => fake()->numberBetween(1, 100),
            'currency' => [$currency->id],
        ];

        $response = $this->actingAs($user)
            ->patch(route('microsites.update', $this->microsite->id), $updatedData);

        $response->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('microsites.index'));

        $this->assertDatabaseHas('microsites', [
            'name' => $updatedData['name'],
            'type_site_id' => $updatedData['type_site_id'],
            'category_id' => $updatedData['category_id'],
            'expiration' => $updatedData['expiration'],
            'user_id' => $this->microsite->user_id,
        ]);
    }
}
