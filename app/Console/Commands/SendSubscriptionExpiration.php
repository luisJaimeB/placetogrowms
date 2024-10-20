<?php

namespace App\Console\Commands;

use App\Models\Invoice;
use App\Models\Suscription;
use App\Notifications\BillingReminder;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class SendSubscriptionExpiration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscription:expiration-notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send billing reminders to users';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {

        $tomorrow = Carbon::tomorrow()->format('Y-m-d');

        $subscriptions = Suscription::where('expiration_date', '=', $tomorrow)
            ->with('user', 'suscriptionPlan')
            ->get();

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
