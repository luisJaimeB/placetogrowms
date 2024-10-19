<?php

namespace App\Console\Commands;

use App\Mail\SendNextPaymentMail;
use App\Models\Suscription;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendNextPaymentEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'email:next-payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a reminder of the date of the next payment by e-mail';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $tomorrow = Carbon::tomorrow()->toDateString();

        $subscriptions = Suscription::whereDate('next_billing_date', $tomorrow)
            ->with(['user', 'microsite', 'suscriptionPlan', 'initialPayment.currency'])
            ->get();

        if ($subscriptions->isNotEmpty()) {
            foreach ($subscriptions as $subscription) {
                //dd($subscription);
                Mail::to($subscription->user->email)->send(new SendNextPaymentMail($subscription));
            }
            $this->info('Correos de recordatorio enviados.');
        } else {
            $this->info('No hay suscripciones con próximo cobro para mañana.');
        }
    }
}
