<?php

namespace App\Console\Commands;

use App\Jobs\ImportInvoicesJob;
use Illuminate\Console\Command;

class ImportInvoices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:import-invoices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import masive invoices';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        ImportInvoicesJob::dispatch();
    }
}
