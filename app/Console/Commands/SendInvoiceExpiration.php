<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Notifications\InvoiceReminder;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;

class SendInvoiceExpiration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoice:expiration-notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notification of soon-to-expire invoices';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');

        $invoices = Invoice::where('expiration_date', '=', $tomorrow)
            ->with('user')
            ->get();

        foreach ($invoices as $invoice) {
            try {
                $this->info('Sending reminder to user ID: '.$invoice->user->id);
                $invoice->user->notify(new InvoiceReminder($invoice));
                $this->info('Reminder sent to user ID: '.$invoice->user->id);
            } catch (Exception $e) {
                $this->error('Failed to send reminder to user ID: '.$invoice->user->id.'. Error: '.$e->getMessage());
            }
        }

        $this->info('Command finished.');
    }
}
