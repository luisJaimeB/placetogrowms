<?php

namespace Tests\Unit\Commands;

use App\Constants\TypesSites;
use App\Models\Currency;
use App\Models\Invoice;
use App\Models\Microsite;
use App\Models\TypeSite;
use Carbon\Carbon;
use Exception;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ApplySurchargeToInvoicesTest extends TestCase
{
    use RefreshDatabase;

    private Microsite $microsite;
    private TypeSite $typeSite;
    private Currency $currency;

    protected function setUp(): void
    {
        parent::setUp();
        $this->currency = Currency::factory()->create();
        $this->siteType = TypeSite::create(['name' => TypesSites::SITE_TYPE_INVOICE->value]);
        $this->microsite = Microsite::factory()->withTypeSiteId($this->siteType->id)->create();
    }

    #[Test]
    public function it_applies_percent_surcharge_to_invoices()
    {
        $invoice = Invoice::factory()->withMicrositeId($this->microsite->id)->create([
            'amount' => 1000,
            'surcharge_date' => Carbon::yesterday(),
            'surcharge_rate' => 'percent',
            'percent' => 10,
            'surcharge_applied' => null,
        ]);

        Artisan::call('invoices:apply-surcharges');

        $invoice->refresh();
        $this->assertEquals(1100, $invoice->amount);
        $this->assertEquals(1, $invoice->surcharge_applied);
    }

    #[Test]
    public function it_applies_additional_amount_surcharge_to_invoices()
    {
        $invoice = Invoice::factory()->for($this->microsite)->create([
            'amount' => 1000,
            'surcharge_date' => Carbon::yesterday(),
            'surcharge_rate' => 'additional amount',
            'additional_amount' => 200,
            'surcharge_applied' => null,
        ]);

        Artisan::call('invoices:apply-surcharges');

        $invoice->refresh();
        $this->assertEquals(1200, $invoice->amount);
        $this->assertEquals(1, $invoice->surcharge_applied);
    }

    #[Test]
    public function it_handles_no_invoices_found()
    {
        $output = Artisan::call('invoices:apply-surcharges');
        $this->assertSame('No se encontraron facturas para aplicar recargos.', trim(Artisan::output()));
    }
}
