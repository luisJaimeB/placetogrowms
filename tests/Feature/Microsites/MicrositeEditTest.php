<?php

namespace Tests\Feature\Microsites;

use App\Constants\Permissions;
use App\Models\Category;
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

class MicrositeEditTest extends TestCase
{
    use HasPermissions;
    use RefreshDatabase;

    private const RESOURCE_NAME = 'microsites.edit';

    private string $route;

    private Microsite $microsite;

    private Category $category;

    private TypeSite $typeSite;

    private Role $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->typeSite = TypeSite::factory()->create();
        $this->category = Category::factory()->create();
        $this->microsite = Microsite::factory()->create([
            'type_site_id' => $this->typeSite->id,
            'category_id' => $this->category->id,
            'user_id' => User::factory()->create()->id,
        ]);
        $this->route = route(self::RESOURCE_NAME, $this->microsite);

        $this->admin = Role::create(['name' => 'Admin']);
        $updatePermission = Permission::create(['name' => Permissions::MICROSITES_UPDATE]);

        $this->admin->givePermissionTo($updatePermission);
    }

    #[Test]
    public function guest_user_cant_edit_an_microsite(): void
    {
        $response = $this->get($this->route);

        $response->assertRedirect('login');
    }

    #[Test]
    public function unauthorized_user_can_not_edit_an_microsite(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->get($this->route);

        $response->assertForbidden();
    }

    #[Test]
    public function authorized_user_can_edit_an_microsite(): void
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
