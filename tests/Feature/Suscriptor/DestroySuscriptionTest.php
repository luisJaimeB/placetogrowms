<?php

namespace Tests\Feature\Suscriptor;

use App\Constants\SuscriptionsStatus;
use App\Models\Payment;
use App\Models\Suscription;
use App\Models\SuscriptionPlan;
use App\Models\User;
use App\Services\PaymentGatewayService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\MockObject\Exception;
use Tests\TestCase;

class DestroySuscriptionTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @throws Exception
     */
    public function destroy_cancels_suscription_and_redirects()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $payment = Payment::factory()->create();
        $plan = SuscriptionPlan::factory()->create();
        $suscription = Suscription::factory()->create([
            'user_id' => $user->id,
            'payment_id' => $payment->id,
            'status' => SuscriptionsStatus::active,
            'plan_id' => $plan->id,
        ]);

        $gatewayMock = $this->createMock(PaymentGatewayService::class);
        $gatewayMock->expects($this->once())
            ->method('cancelToken')
            ->willReturn('token_cancelled');

        $this->app->instance(PaymentGatewayService::class, $gatewayMock);

        $response = $this->delete(route('subscriptions.destroy', $suscription->id));

        $response->assertRedirect(route('subscriptions.index', ['tokenCancelation' => 'token_cancelled']));

        $this->assertDatabaseHas('suscriptions', [
            'id' => $suscription->id,
            'status' => SuscriptionsStatus::canceled,
        ]);
    }

    public function destroy_returns_error_on_exception()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $payment = Payment::factory()->create();
        $suscription = Suscription::factory()->create([
            'user_id' => $user->id,
            'payment_id' => $payment->id,
            'status' => SuscriptionsStatus::active,
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
