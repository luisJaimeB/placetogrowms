<?php

namespace Tests\Feature\Payments;

use App\Constants\TypesSites;
use App\Factories\PaymentFactory;
use App\Models\Microsite;
use App\Models\Payment;
use App\Models\TypeSite;
use App\Payments\PlaceToPayGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Mockery;
use Tests\TestCase;

class HandleReturnPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_handle_return()
    {
        $responseData = [
            'status' => [
                'status' => 'APPROVED',
                'reason' => '00',
                'message' => 'La petición ha sido aprobada exitosamente',
                'date' => '2024-08-04T11:07:02-05:00',
            ],
            'request' => [
                'payment' => [
                    'reference' => 'test_reference',
                ],
                'ipAddress' => '127.0.0.1',
                'userAgent' => 'Mozilla/5.0',
            ],
            'payment' => [
                [
                    'status' => [
                        'status' => 'APPROVED',
                        'reason' => '00',
                        'message' => 'Aprobada',
                        'date' => '2024-08-03T18:41:52-05:00',
                    ],
                    'authorization' => '431662',
                ],
            ],
        ];

        Http::fake([
            '*' => Http::response($responseData, 200),
        ]);

        $gatewayMock = Mockery::mock(PlaceToPayGateway::class);
        $gatewayMock->shouldReceive('getInfomation')
            ->andReturn($responseData);

        $this->mock(PaymentFactory::class, function ($mock) use ($gatewayMock) {
            $mock->shouldReceive('create')
                ->with('placetopay', Mockery::type('array'))
                ->andReturn($gatewayMock);
        });

        $siteType = TypeSite::create(['name' => TypesSites::SITE_TYPE_INVOICE->value]);
        $microsite = Microsite::factory()->withTypeSiteId($siteType->id)->create();
        $payment = Payment::factory()->withMicrositeId($microsite)->create([
            'payment_method' => 'placetopay',
            'return_id' => 'test_return_id',
        ]);

        $response = $this->get(route('payments.return', $payment->return_id));

        $response->assertStatus(302);
        $response->assertRedirect(route('payment.show', ['payment' => $payment->id]));
        $response->assertSessionHas('success', 'Payment processed successfully');

        $this->assertDatabaseHas('payments', [
            'id' => $payment->id,
            'status' => 'APPROVED',
            'reference' => 'test_reference',
            'ip_address' => '127.0.0.1',
            'user_agent' => 'Mozilla/5.0',
            'cus_code' => '431662',
        ]);
    }

    public function test_handle_return_error()
    {
        $siteType = TypeSite::create(['name' => TypesSites::SITE_TYPE_INVOICE->value]);
        $microsite = Microsite::factory()->withTypeSiteId($siteType->id)->create();
        Payment::factory()->withMicrositeId($microsite)->create();

        $response = $this->get(route('payments.return', 'invalid_return_id'));

        $response->assertStatus(404);
    }

}
