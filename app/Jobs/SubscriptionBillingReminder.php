<?php

namespace App\Jobs;

use App\Models\Suscription;
use App\Notifications\BillingReminder;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SubscriptionBillingReminder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $tomorrow = Carbon::tomorrow();

        $subscriptions = Suscription::where('next_billing_date', '=', $tomorrow)
            ->with('user', 'suscriptionPlan')
            ->get();

        foreach ($subscriptions as $subscription) {
            $subscription->user->notify(new BillingReminder($subscription));
        }
    }
}
