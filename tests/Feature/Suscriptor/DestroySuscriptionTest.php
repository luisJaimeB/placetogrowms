<?php

namespace Tests\Feature\Suscriptor;

use App\Constants\Permissions;
use App\Constants\Roles;
use App\Constants\SuscriptionsStatus;
use App\Constants\TypesSites;
use App\Models\Microsite;
use App\Models\Payment;
use App\Models\Suscription;
use App\Models\SuscriptionPlan;
use App\Models\TypeSite;
use App\Models\User;
use App\Services\PaymentGatewayService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\MockObject\Exception;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DestroySuscriptionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @throws Exception
     */
    public function destroy_cancels_suscription_and_redirects(): void
    {
        $admin = Role::create(['name' => Roles::ADMIN->value]);
        $customer = Role::create(['name' => Roles::CUSTOMER->value]);
        $permission = Permission::create(['name' => Permissions::SUBSCRIPTIONS_DELETE->value]);
        $permission2 = Permission::create(['name' => Permissions::INVOICES_INDEX->value]);

        $admin->givePermissionTo($permission);
        $customer->givePermissionTo($permission2);
        $user = User::factory()->create();
        $user->assignRole($admin);
        $this->actingAs($user);

        $siteType = TypeSite::create(['name' => TypesSites::SITE_TYPE_SUBSCRIPTION->value]);
        $microsite = Microsite::factory()->withTypeSiteId($siteType->id)->create();

        $payment = Payment::factory()->withMicrositeId($microsite)->create();
        $plan = SuscriptionPlan::factory()->withMicrositeId($microsite)->create();
        $suscription = Suscription::factory()->withMicrositeId($microsite)->create([
            'user_id' => $user->id,
            'payment_id' => $payment->id,
            'status' => SuscriptionsStatus::ACTIVE->value,
            'plan_id' => $plan->id,
        ]);

        $gatewayMock = $this->createMock(PaymentGatewayService::class);
        $gatewayMock->expects($this->once())
            ->method('cancelToken')
            ->willReturn('token_cancelled');

        $this->app->instance(PaymentGatewayService::class, $gatewayMock);

        $response = $this->actingAs($user)->delete(route('subscriptions.destroy', $suscription->id));

        $response->assertRedirect(route('subscriptions.index', ['tokenCancelation' => 'token_cancelled']));

        $this->assertDatabaseHas('suscriptions', [
            'id' => $suscription->id,
            'status' => SuscriptionsStatus::CANCELLED->value,
        ]);
    }

    public function destroy_returns_error_on_exception(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $siteType = TypeSite::create(['name' => TypesSites::SITE_TYPE_SUBSCRIPTION->value]);
        $microsite = Microsite::factory()->withTypeSiteId($siteType->id)->create();
        $payment = Payment::factory()->withMicrositeId($microsite)->create();
        $plan = SuscriptionPlan::factory()->withMicrositeId($microsite->id)->create();
        $suscription = Suscription::factory()->create([
            'user_id' => $user->id,
            'payment_id' => $payment->id,
            'status' => SuscriptionsStatus::ACTIVE->value,
            'plan' => $plan->id
        ]);

        $gatewayMock = $this->createMock(PaymentGatewayService::class);
        $gatewayMock->expects($this->once())
            ->method('cancelToken')
            ->will($this->throwException(new \Exception('Error al cancelar el token')));

        $this->app->instance(PaymentGatewayService::class, $gatewayMock);

        $response = $this->delete(route('subscriptions.destroy', $suscription->id));

        $response->assertStatus(400);
        $response->assertJson(['error' => 'Error al cancelar el token']);
    }
}
