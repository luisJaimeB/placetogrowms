<?php

namespace Tests\Feature\Payments;

use App\Factories\PaymentFactory;
use App\Http\Controllers\PaymentController;
use App\Http\Requests\PaymentCreateRequest;
use App\Models\Currency;
use App\Models\Microsite;
use App\Payments\PlaceToPayGateway;
use Illuminate\Container\Container;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Mockery;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ProcessPaymentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Config::set('payments.placetopay_endpoint', 'http://mock.endpoint');
        Config::set('payments.placetopay_login', 'test_login');
        Config::set('payments.placetopay_secret', 'test_secret');
    }

    public function testProcessPaymentSuccess()
    {
        $requestData = $this->getRequestData();

        $request = $this->mockRequest($requestData);
        $this->mockHttp(['status' => ['status' => 'OK'], 'processUrl' => 'http://process.url', 'requestId' => 123456]);
        $gateway = $this->mockGateway(['processUrl' => 'http://process.url']);
        $this->bindFactory($gateway);

        $controller = new PaymentController();
        $response = $controller->processPayment($request);

        $this->assertEquals(Response::HTTP_OK, $response->status());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['redirect_url' => 'http://process.url']),
            $response->getContent()
        );
    }

    public function testProcessPaymentFailure()
    {
        $requestData = $this->getRequestData();

        $request = $this->mockRequest($requestData);
        $this->mockHttp(['status' => ['status' => 'FAILED', 'message' => 'Payment failed']]);
        $gateway = $this->mockGateway(['status' => ['status' => 'FAILED', 'message' => 'Payment failed']]);
        $this->bindFactory($gateway);

        $controller = new PaymentController();
        $response = $controller->processPayment($request);

        $this->assertEquals(Response::HTTP_BAD_REQUEST, $response->status());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['error' => 'Payment failed']),
            $response->getContent()
        );
    }

    private function getRequestData(): array
    {
        $currency = Currency::factory()->create();
        $microsite = Microsite::factory()->create();

        return [
            'paymentMethod' => 'placetopay',
            'currency' => $currency->id,
            'description' => 'Test payment',
            'amount' => 100,
            'expiration' => 60,
            'userIp' => '127.0.0.1',
            'userAgent' => 'Mozilla/5.0',
            'name' => 'John',
            'lastName' => 'Doe',
            'email' => 'john.doe@example.com',
            'phone' => '+123456789',
            'type' => 1,
            'micrositeId' => $microsite->id,
        ];
    }

    private function mockRequest(array $requestData): PaymentCreateRequest|(Mockery\MockInterface&Mockery\LegacyMockInterface)
    {
        $request = Mockery::mock(PaymentCreateRequest::class);
        $request->shouldReceive('input')->with('paymentMethod')->andReturn('placetopay');
        $request->shouldReceive('currency')->andReturn(1);
        $request->shouldReceive('ip')->andReturn('127.0.0.1');
        $request->shouldReceive('header')->with('User-Agent')->andReturn('Mozilla/5.0');
        $request->shouldReceive('validated')->andReturn($requestData);
        $request->shouldReceive('all')->andReturn($requestData);

        return $request;
    }

    private function mockGateway(array $response): PlaceToPayGateway|(Mockery\MockInterface&Mockery\LegacyMockInterface)
    {
        $gateway = Mockery::mock(PlaceToPayGateway::class);
        $gateway->shouldReceive('pay')->with(Mockery::any())->andReturn($response);

        return $gateway;
    }

    private function bindFactory($gateway): void
    {
        Container::getInstance()->bind(PaymentFactory::class, function () use ($gateway) {
            $mockFactory = Mockery::mock(PaymentFactory::class);
            $mockFactory->shouldReceive('create')->with('placetopay', Mockery::any())->andReturn($gateway);

            return $mockFactory;
        });
    }

    private function mockHttp(array $response): void
    {
        Http::fake([
            'http://mock.endpoint/api/session' => Http::response($response, 200),
        ]);
    }
}
