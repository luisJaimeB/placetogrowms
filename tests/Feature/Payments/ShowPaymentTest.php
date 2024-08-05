<?php

namespace Tests\Feature\Payments;

use App\Models\Payment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function testPaymentResultShow()
    {
        $payment = Payment::factory()->create();
        $response = $this->get(route('payments.show', $payment));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Payments/ShowPayment')
            ->has('payment')
        );
    }
}
