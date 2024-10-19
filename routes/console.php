<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

Artisan::command('invoices:update-status', function () {
    Artisan::call('invoices:update-status');
})->purpose('Update the status of overdue invoices')->daily();

Artisan::command('subscriptions:charge', function () {
    Artisan::call('subscriptions:charge');
})->purpose('Charge subscriptions based on their next billing date')->daily();
