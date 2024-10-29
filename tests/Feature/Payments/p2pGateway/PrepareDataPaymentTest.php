<?php

namespace Tests\Feature\Payments\p2pGateway;

use App\Payments\PlaceToPayGateway;
use ReflectionException;
use Tests\TestCase;

class PrepareDataPaymentTest extends TestCase
{
    public function testPrepareAuth()
    {
        $data = [];
        $gateway = new PlaceToPayGateway($data);

        $reflection = new \ReflectionClass($gateway);
        $method = $reflection->getMethod('prepareAuth');

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
            'amount' => 100,
        ];
        $gateway = new PlaceToPayGateway($data);

        $reflection = new \ReflectionClass($gateway);
        $method = $reflection->getMethod('preparePaymentBody');

        $paymentBody = $method->invoke($gateway, $data);

        $this->assertArrayHasKey('reference', $paymentBody);
        $this->assertArrayHasKey('description', $paymentBody);
        $this->assertArrayHasKey('amount', $paymentBody);
        $this->assertEquals('USD', $paymentBody['amount']['currency']);
        $this->assertEquals(100, $paymentBody['amount']['total']);
    }

    /**
     * @throws ReflectionException
     */
    public function testReturnUrl()
    {
        $data = [];

        $gateway = new PlaceToPayGateway($data);

        $reflection = new \ReflectionClass($gateway);
        $method = $reflection->getMethod('returnUrl');

        $url = $method->invoke($gateway, $data);

        $this->assertStringContainsString(route('payments.return', ''), $url);

        $randomReturn = strtoupper(substr(md5(uniqid(mt_rand(), true)), 0, 10));
        $expectedUrl = route('payments.return', $randomReturn);
        $this->assertStringContainsString($randomReturn, $expectedUrl);
    }

    public function testPrepareTokenData()
    {
        $data = [
            'token' => 'sample_token_value',
        ];

        $gateway = new PlaceToPayGateway($data);

        $reflection = new \ReflectionClass($gateway);
        $method = $reflection->getMethod('prepareTokenData');

        $tokenData = $method->invoke($gateway, $data);

        $this->assertArrayHasKey('token', $tokenData);
        $this->assertArrayHasKey('token', $tokenData['token']);
        $this->assertEquals('sample_token_value', $tokenData['token']['token']);
    }

    public function testPrepareOptionalFields()
    {
        $data = [
            'optional_fields' => [
                ['field' => 'field1', 'value' => 'value1'],
                ['field' => 'field2', 'value' => 'value2'],
                ['invalid_field' => 'value3'],
            ],
        ];

        $gateway = new PlaceToPayGateway($data);

        $reflection = new \ReflectionClass($gateway);
        $method = $reflection->getMethod('prepareOptionalFields');

        $optionalFields = $method->invoke($gateway, $data);

        $this->assertCount(2, $optionalFields); // Debe haber 2 campos válidos

        $this->assertEquals('field1', $optionalFields[0]['keyword']);
        $this->assertEquals('value1', $optionalFields[0]['value']);
        $this->assertEquals('none', $optionalFields[0]['displayOn']);

        $this->assertEquals('field2', $optionalFields[1]['keyword']);
        $this->assertEquals('value2', $optionalFields[1]['value']);
        $this->assertEquals('none', $optionalFields[1]['displayOn']);
    }

    public function testPreparePayerData()
    {
        $data = [
            'documentType' => 'ID',
            'document' => '123456789',
            'name' => 'John',
            'surname' => 'Doe',
            'email' => 'john.doe@example.com',
            'mobile' => '1234567890',
        ];

        $gateway = new PlaceToPayGateway($data);

        $reflection = new \ReflectionClass($gateway);
        $method = $reflection->getMethod('preparePayerData');

        $payerData = $method->invoke($gateway, $data);

        $this->assertArrayHasKey('documentType', $payerData);
        $this->assertArrayHasKey('document', $payerData);
        $this->assertArrayHasKey('name', $payerData);
        $this->assertArrayHasKey('surname', $payerData);
        $this->assertArrayHasKey('email', $payerData);
        $this->assertArrayHasKey('mobile', $payerData);

        $this->assertEquals('ID', $payerData['documentType']);
        $this->assertEquals('123456789', $payerData['document']);
        $this->assertEquals('John', $payerData['name']);
        $this->assertEquals('Doe', $payerData['surname']);
        $this->assertEquals('john.doe@example.com', $payerData['email']);
        $this->assertEquals('1234567890', $payerData['mobile']);
    }

    public function testPrepareSuscriptionBody()
    {
        $data = [
            'description' => 'Test Subscription',
        ];

        $gateway = new PlaceToPayGateway($data);

        $reflection = new \ReflectionClass($gateway);
        $method = $reflection->getMethod('prepareSuscriptionBody');

        $subscriptionBody = $method->invoke($gateway, $data);

        $this->assertArrayHasKey('reference', $subscriptionBody);
        $this->assertArrayHasKey('description', $subscriptionBody);
        $this->assertEquals('Test Subscription', $subscriptionBody['description']);
        $this->assertStringStartsWith('subs-', $subscriptionBody['reference']);
        $this->assertEquals(15, strlen($subscriptionBody['reference']));
    }
}
