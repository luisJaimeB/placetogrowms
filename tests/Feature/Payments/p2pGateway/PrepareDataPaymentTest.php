<?php

namespace Tests\Feature\Payments\p2pGateway;

use App\Payments\PlaceToPayGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PrepareDataPaymentTest extends TestCase
{
    public function testPrepareAuth()
    {
        $data = [];
        $gateway = new PlaceToPayGateway($data);

        $reflection = new \ReflectionClass($gateway);
        $method = $reflection->getMethod('prepareAuth');
        $method->setAccessible(true);

        $auth = $method->invoke($gateway);

        $this->assertArrayHasKey('login', $auth);
        $this->assertArrayHasKey('tranKey', $auth);
        $this->assertArrayHasKey('nonce', $auth);
        $this->assertArrayHasKey('seed', $auth);
    }

    public function testPreparePaymentBody()
    {
        $data = [
            'description' => 'Test Payment',
            'currencyCode' => 'USD',
            'amount' => 100
        ];
        $gateway = new PlaceToPayGateway($data);

        $reflection = new \ReflectionClass($gateway);
        $method = $reflection->getMethod('preparePaymentBody');
        $method->setAccessible(true);

        $paymentBody = $method->invoke($gateway, $data);

        $this->assertArrayHasKey('reference', $paymentBody);
        $this->assertArrayHasKey('description', $paymentBody);
        $this->assertArrayHasKey('amount', $paymentBody);
        $this->assertEquals('USD', $paymentBody['amount']['currency']);
        $this->assertEquals(100, $paymentBody['amount']['total']);
    }

}
