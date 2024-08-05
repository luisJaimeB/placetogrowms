<?php

namespace Tests\Feature\Payments\p2pGateway;

use App\Factories\PaymentFactory;
use App\Payments\PlaceToPayGateway;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class GetInformationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockPaymentFactory();
    }

    protected function mockPaymentFactory(): void
    {
        $mockGateway = Mockery::mock(PlaceToPayGateway::class);

        $mockGateway->shouldReceive('getInfomation')
            ->with(['request_id' => '12345'])
            ->andReturn([
                'status' => ['status' => 'OK'],
                'request' => ['payment' => ['reference' => 'pay-12345']],
                'payment' => [['authorization' => 'auth-12345']]
            ]);

        $this->app->bind(PaymentFactory::class, function () use ($mockGateway) {
            return Mockery::mock(PaymentFactory::class)
                ->shouldReceive('create')
                ->with('placetopay', Mockery::type('array'))
                ->andReturn($mockGateway)
                ->getMock();
        });
    }

    /**
     * @throws Exception
     */
    public function testGetInformationSuccess()
    {
        $data = [
            'request_id' => '12345'
        ];

        $gateway = app(PaymentFactory::class)->create('placetopay', $data);
        $response = $gateway->getInfomation($data);

        $this->assertArrayHasKey('status', $response);
        $this->assertEquals('OK', $response['status']['status']);
    }

    public function testGetInformationFailed()
    {
        $mockGateway = Mockery::mock(PlaceToPayGateway::class);

        $mockGateway->shouldReceive('getInfomation')
            ->with(['request_id' => '12345'])
            ->andThrow(new Exception('Error al procesar el pago'));

        $this->app->bind(PaymentFactory::class, function () use ($mockGateway) {
            return Mockery::mock(PaymentFactory::class)
                ->shouldReceive('create')
                ->with('placetopay', Mockery::type('array'))
                ->andReturn($mockGateway)
                ->getMock();
        });

        $data = [
            'request_id' => '12345'
        ];

        $gateway = app(PaymentFactory::class)->create('placetopay', $data);

        $this->expectException(Exception::class);
        $gateway->getInfomation($data);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
