<?php

namespace App\Console\Commands;

use App\Constants\SuscriptionsStatus;
use App\Jobs\CollectSubscription;
use App\Models\Suscription;
use Carbon\Carbon;
use Exception;
use Illuminate\Console\Command;
use Throwable;

class ChargeSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:charge';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Charge subscriptions based on their next billing date';

    /**
     * Execute the console command.
     *
     * @throws Exception|Throwable
     */
    public function handle(): void
    {
        $today = Carbon::today();

        $subscriptions = Suscription::query()
            ->where('next_billing_date', '<=', $today)
            ->where('expiration_date', '>=', $today)
            ->whereIn('status', [SuscriptionsStatus::ACTIVE, SuscriptionsStatus::FREEZE])
            ->with(['initialPayment', 'microsite', 'suscriptionPlan'])
            ->get();

        foreach ($subscriptions as $subscription) {
            CollectSubscription::dispatch($subscription);
        }
    }
}
