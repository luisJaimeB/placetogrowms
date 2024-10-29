<?php

namespace App\Console\Commands;

use App\Constants\InvoicesStatus;
use App\Models\Invoice;
use Carbon\Carbon;
use Illuminate\Console\Command;

class UpdateInvoiceStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:update-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the status of overdue invoices';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $invoices = Invoice::where('expiration_date', '<', Carbon::now())
            ->where('status', '!=', InvoicesStatus::paid)
            ->update(['status' => InvoicesStatus::expired]);

        $this->info('Invoice statuses updated successfully!');
    }
}
