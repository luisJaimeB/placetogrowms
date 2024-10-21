<?php

namespace Tests\Feature\Payments;

use App\Constants\TypesSites;
use App\Models\Microsite;
use App\Models\Payment;
use App\Models\TypeSite;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ShowPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function testPaymentResultShow()
    {
        $siteType = TypeSite::create(['name' => TypesSites::SITE_TYPE_INVOICE->value]);
        $microsite = Microsite::factory()->withTypeSiteId($siteType->id)->create();
        $payment = Payment::factory()->withMicrositeId($microsite)->create();
        $response = $this->get(route('payments.show', $payment));

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page
            ->component('Payments/ShowPayment')
            ->has('payment')
        );
    }
}
