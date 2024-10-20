<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Schedule::command('invoices:update-status')->purpose('Update the status of overdue invoices')->daily();
Schedule::command('subscriptions:charge')->purpose('Charge subscriptions based on their next billing date')->daily();
Schedule::command('subscription:expiration-notify')->purpose('Notification of soon-to-expire subscriptions')->daily();
Schedule::command('invoice:expiration-notify')->purpose('Notification of soon-to-expire subscriptions')->daily();
Schedule::command('app:import-invoices')->purpose('Import invoices ')->everyMinute();
