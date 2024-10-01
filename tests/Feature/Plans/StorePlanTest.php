<?php

namespace Tests\Feature\Plans;

use App\Constants\Periodicities;
use App\Constants\Permissions;
use App\Constants\Roles;
use App\Models\Microsite;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasPermissions;
use Tests\TestCase;

class StorePlanTest extends TestCase
{
    use HasPermissions;
    use RefreshDatabase;
    use WithFaker;

    private const RESOURCE_NAME = 'planes.store';

    private string $route;

    private Role $adminrole;

    private Permission $permission;

    private User $user;

    private Microsite $microsite;

    protected function setUp(): void
    {
        parent::setUp();

        $this->route = route(self::RESOURCE_NAME);

        $this->microsite = Microsite::factory()->create();

        $this->user = User::factory()->create();
        $this->adminrole = Role::create(['name' => Roles::ADMIN]);
        $this->permission = Permission::create(['name' => Permissions::PLANES_CREATE]);

        $this->adminrole->givePermissionTo($this->permission);
        $this->user->assignRole($this->adminrole);
    }

    public function test_guest_user_can_not_access_to_planes_store(): void
    {
        $response = $this->post($this->route);

        $response->assertRedirect('login');
    }

    public function test_unauthorized_user_cant_access_to_planes_store(): void
    {
        /** @var \App\Models\User $user */
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->post($this->route);

        $response->assertForbidden();
    }

    public function test_authorized_user_can_store_a_plan(): void
    {
        Carbon::setTestNow(now());

        $data = [
            'name' => $this->faker->word.' '.$this->faker->word,
            'periodicity' => $this->faker->randomElement(Periodicities::toArray()),
            'interval' => $this->faker->word,
            'amount' => $this->faker->numberBetween(1, 9999999999),
            'next_payment' => $this->faker->dateTimeBetween('now', '+1 year')->format('Y-m-d'),
            'due_date' => $this->faker->dateTimeBetween('+1 year', '+2 years')->format('Y-m-d'),
            'microsite_id' => $this->microsite->id,
            'items' => ['item1', 'item2'],
            'user_id' => $this->user->id,
        ];

        $response = $this->actingAs($this->user)
            ->post($this->route, $data);

        $response->assertSessionDoesntHaveErrors()
            ->assertRedirect(route('planes.index'));

        $this->assertDatabaseHas('suscription_plans', [
            'name' => $data['name'],
            'periodicity' => $data['periodicity'],
        ]);
    }
}
