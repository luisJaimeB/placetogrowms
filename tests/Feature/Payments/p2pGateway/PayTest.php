<?php

namespace Tests\Feature\Payments\p2pGateway;

use App\Constants\TypesSites;
use App\Models\Currency;
use App\Models\Microsite;
use App\Models\TypeSite;
use App\Payments\PlaceToPayGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use Throwable;

class PayTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $data;

    protected function setUp(): void
    {
        parent::setUp();

        $currency = Currency::factory()->create();
        $siteType = TypeSite::create(['name' => TypesSites::SITE_TYPE_INVOICE->value]);
        $microsite = Microsite::factory()->withTypeSiteId($siteType->id)->create();

        $this->data = [
            'description' => 'Test Payment',
            'currency' => $currency->id,
            'currencyCode' => 'USD',
            'amount' => 100,
            'name' => 'John',
            'lastName' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '1234567890',
            'expiration' => 10,
            'userIp' => '127.0.0.1',
            'userAgent' => 'Mozilla/5.0',
            'paymentMethod' => 'placetopay',
            'type' => 1,
            'micrositeId' => $microsite->id,
            'requestId' => 123456,
            'buyer_id_type' => 1,
            'buyer_id' => '107156541',
        ];

        Config::set('payments.placetopay_endpoint', 'http://test.endpoint');
        Config::set('payments.placetopay_login', 'test_login');
        Config::set('payments.placetopay_secret', 'test_secret');
    }

    /**
     * @throws Throwable
     */
    public function testPaySuccess()
    {
        $gateway = new PlaceToPayGateway($this->data);

        Http::fake([
            'http://test.endpoint/api/session' => Http::response(['status' => ['status' => 'OK'], 'processUrl' => 'http://process.url', 'requestId' => 123456], 200),
        ]);

        $response = $gateway->pay($this->data);

        $this->assertIsArray($response);
        $this->assertArrayHasKey('processUrl', $response);
    }

    /**
     * @throws Throwable
     */
    public function testPayFailed()
    {
        $this->data['expiration'] = 2;
        $gateway = new PlaceToPayGateway($this->data);
        Http::fake([
            'http://test.endpoint/api/session' => Http::response([
                'status' => ['status' => 'FAILED', 'message' => 'Payment failed'],
            ], 200),
        ]);

        $response = $gateway->pay($this->data);

        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('FAILED', $response['status']['status']);
        $this->assertEquals('Payment failed', $response['status']['message']);
    }
}
