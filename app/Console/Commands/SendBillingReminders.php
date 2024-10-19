<?php

namespace App\Console\Commands;

use App\Models\Suscription;
use App\Notifications\BillingReminder;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendBillingReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'billing:reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send billing reminders to users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Command started.');

        $tomorrow = Carbon::tomorrow()->format('Y-m-d');

        $subscriptions = Suscription::where('next_billing_date', '=', $tomorrow)
            ->with('user', 'suscriptionPlan')
            ->get();

        $this->info('Found ' . $subscriptions->count() . ' subscriptions.');

        foreach ($subscriptions as $subscription) {
            try {
                $this->info('Sending reminder to user ID: ' . $subscription->user->id);
                $subscription->user->notify(new BillingReminder($subscription));
                $this->info('Reminder sent to user ID: ' . $subscription->user->id);
            } catch (Exception $e) {
                $this->error('Failed to send reminder to user ID: ' . $subscription->user->id . '. Error: ' . $e->getMessage());
            }
        }

        $this->info('Command finished.');
    }
}
