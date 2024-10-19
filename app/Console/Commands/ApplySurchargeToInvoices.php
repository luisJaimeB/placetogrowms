<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;

class ApplySurchargeToInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:apply-surcharges';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Apply surcharges to invoices whose surcharge_date has already passed.';

    public function __construct()
    {
        parent::__construct();
    }
    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        try {
            $invoices = Invoice::where('surcharge_date', '<=', Carbon::today())
                ->whereNull('surcharge_applied')
                ->get();

            if ($invoices->isEmpty()) {
                $this->info('No se encontraron facturas para aplicar recargos.');
                return;
            }

            foreach ($invoices as $invoice) {
                if ($invoice->surcharge_rate === 'percent') {
                    $surchargeAmount = $invoice->amount * ($invoice->percent / 100);
                    $invoice->amount += $surchargeAmount;
                } elseif ($invoice->surcharge_rate === 'additional amount') {
                    $invoice->amount += $invoice->additional_amount;
                }

                $invoice->surcharge_applied = true;
                $invoice->save();
            }

            $this->info('Recargos aplicados a las facturas exitosamente.');
        } catch (Exception $e) {
            $this->error('Ocurrió un error al aplicar los recargos: ' . $e->getMessage());
        }
    }
}
