<?php

namespace Tests\Feature\Payments;

use App\Models\Microsite;
use App\Models\TypeSite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreatePaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_create_payment_view_for_donation_microsite()
    {
        $this->runTestForMicrositeType('Donation', 'Payments/CreatePayment');
    }

    public function test_create_payment_view_for_invoice_microsite()
    {
        $this->runTestForMicrositeType('Invoice', 'Payments/InvoicePayment');
    }

    public function test_create_payment_view_for_subscription_microsite()
    {
        $this->runTestForMicrositeType('Subscription', 'Payments/SubscriptionPayment');
    }

    private function runTestForMicrositeType($typeName, $expectedComponent)
    {
        $siteType = TypeSite::create(['name' => $typeName]);
        $microsite = Microsite::factory()->withTypeSiteId($siteType->id)->create();

        $response = $this->get(route('payments.create', $microsite->id));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component($expectedComponent)
            ->has('microsite', fn ($page) => $page->where('id', $microsite->id)->etc())
        );
    }
}
