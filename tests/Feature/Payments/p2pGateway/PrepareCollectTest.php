<?php

namespace Tests\Feature\Payments\p2pGateway;

use App\Constants\Currencies;
use App\Models\Currency;
use App\Payments\PlaceToPayGateway;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PrepareCollectTest extends TestCase
{
    use RefreshDatabase;

    private Currency $currency;

    protected function setUp(): void
    {
        parent::setUp();
        $this->currency = Currency::factory()->create(['code' => Currencies::USD->value]);
    }

    public function test_prepareCollect()
    {
        $data = [
            'initial_payment' => [
                'currency_id' => $this->currency->id,
                'amount' => 100.00,
                'description' => 'Descripción de prueba',
            ],
            'payer' => [
                'documentType' => 'CC',
                'document' => '123456789',
                'name' => 'John',
                'surname' => 'Doe',
                'email' => 'john@example.com',
                'mobile' => '+1234567890',
            ],
            'token' => 'mocked-token',
            'expiration' => 30,
            'microsite' => [
                'expiration' => 30,
            ],
        ];

        $placeToPayGateway = new PlaceToPayGateway([]);

        $result = $placeToPayGateway->prepareCollect($data);

        $expectedDescription = 'Pago suscripción - '.Carbon::today();
        $this->assertEquals($expectedDescription, $result['payment']['description']);
        $this->assertEquals($data['payer'], $result['payer']);
        $this->assertNotEmpty($result['expiration']);
        $this->assertNotEmpty($result['returnUrl']);
        $this->assertNotEmpty($result['ipAddress']);
        $this->assertNotEmpty($result['userAgent']);
    }
}
