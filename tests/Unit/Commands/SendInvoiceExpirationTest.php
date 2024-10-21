<?php

namespace Tests\Unit\Commands;

use App\Console\Commands\SendInvoiceExpiration;
use App\Constants\TypesSites;
use App\Models\Currency;
use App\Models\Invoice;
use App\Models\Microsite;
use App\Models\TypeSite;
use App\Models\User;
use App\Notifications\InvoiceReminder;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;
use Mockery;

class SendInvoiceExpirationTest extends TestCase
{
    use RefreshDatabase;

    public function test_command_sends_invoice_expiration_reminders()
    {
        // Mock today's date to ensure consistency in the test
        Carbon::setTestNow(Carbon::create(2024, 10, 21));

        // Fake notifications to prevent real ones from being sent
        Notification::fake();

        // Create a mock user
        $user = User::factory()->create();

        // Create an invoice with an expiration date of tomorrow
        $currency = Currency::factory()->create();
        $siteType = TypeSite::create(['name' => TypesSites::SITE_TYPE_INVOICE->value]);
        $microsite = Microsite::factory()->withTypeSiteId($siteType->id)->create();
        $invoice = Invoice::factory()->withMicrositeId($microsite)->create([
            'user_id' => $user->id,
            'currency_id' => $currency->id,
            'expiration_date' => Carbon::tomorrow()->format('Y-m-d'),
        ]);

        // Execute the command
        $this->artisan(SendInvoiceExpiration::class)
            ->expectsOutput('Sending reminder to user ID: ' . $user->id)
            ->expectsOutput('Reminder sent to user ID: ' . $user->id)
            ->expectsOutput('Command finished.')
            ->assertExitCode(0);

        // Assert that a notification was sent to the correct user
        Notification::assertSentTo($user, InvoiceReminder::class, function ($notification, $channels) use ($invoice) {
            return $notification->getInvoice()->is($invoice);
        });
    }

    public function test_command_handles_no_invoices_for_tomorrow()
    {
        // Mock today's date
        Carbon::setTestNow(Carbon::create(2024, 10, 21));

        // Fake notifications to prevent real ones from being sent
        Notification::fake();

        // Ensure no invoices exist for tomorrow
        Invoice::where('expiration_date', Carbon::tomorrow()->format('Y-m-d'))->delete();

        // Execute the command
        $this->artisan(SendInvoiceExpiration::class)
            ->expectsOutput('Command finished.')
            ->assertExitCode(0);

        // Assert that no notifications were sent
        Notification::assertNothingSent();
    }

    public function test_command_logs_error_when_notification_fails()
    {
        // Mock today's date
        Carbon::setTestNow(Carbon::create(2024, 10, 21));

        // Fake notifications
        Notification::fake();

        // Create a mock user
        $user = User::factory()->create();

        $currency = Currency::factory()->create();
        $siteType = TypeSite::create(['name' => TypesSites::SITE_TYPE_INVOICE->value]);
        $microsite = Microsite::factory()->withTypeSiteId($siteType->id)->create();
        // Create an invoice that expires tomorrow
        $invoice = Invoice::factory()->withMicrositeId($microsite)->create([
            'user_id' => $user->id,
            'currency_id' =>$currency->id,
            'expiration_date' => Carbon::tomorrow()->format('Y-m-d'),
        ]);

        // Mock an exception when sending the notification
        Notification::shouldReceive('send')
            ->andThrow(new \Exception('Notification Error'));

        // Execute the command
        $this->artisan(SendInvoiceExpiration::class)
            ->expectsOutput('Sending reminder to user ID: ' . $user->id)
            ->expectsOutput('Failed to send reminder to user ID: ' . $user->id . '. Error: Notification Error')
            ->expectsOutput('Command finished.')
            ->assertExitCode(0);
    }
}
