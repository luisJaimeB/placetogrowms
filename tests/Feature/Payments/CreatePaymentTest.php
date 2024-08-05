<?php

namespace Tests\Feature\Payments;

use App\Models\Microsite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreatePaymentTest extends TestCase
{
    use RefreshDatabase;

    public function testCreate()
    {
        $microsite = Microsite::factory()->create();
        $response = $this->get(route('payments.create', $microsite));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Payments/CreatePayment')
            ->has('microsite')
            ->has('currencies')
        );
    }
}
