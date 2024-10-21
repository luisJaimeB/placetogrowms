<?php

namespace Tests\Unit\Imports;

use App\Constants\InvoicesStatus;
use App\Constants\SurchargeRate;
use App\Imports\InvoicesImport;
use App\Models\Invoice;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class InvoicesImportTest extends TestCase
{
    public function test_import_creates_invoice()
    {
        $user = User::factory()->create();
        $row = [
            'microsite_id' => 1,
            'order_number' => '123456',
            'identification_type_id' => 1,
            'identification_number' => '123456789',
            'debtor_name' => 'John Doe',
            'email' => 'john@example.com',
            'description' => 'Test Invoice',
            'currency_id' => 1,
            'amount' => 100.00,
            'expiration_date' => now()->addDays(30)->format('Y-m-d'),
            'surcharge_date' => now()->addDays(20)->format('Y-m-d'),
            'surcharge_rate' => SurchargeRate::PERCENT->value,
            'percent' => 10,
            'additional_amount' => 1000,
        ];

        $userId = $user->id;

        $import = new InvoicesImport($userId);

        $invoice = $import->model($row);

        $this->assertInstanceOf(Invoice::class, $invoice);
        $this->assertEquals($row['microsite_id'], $invoice->microsite_id);
        $this->assertEquals(InvoicesStatus::active, $invoice->status);
        $this->assertEquals($row['order_number'], $invoice->order_number);
        $this->assertEquals($row['identification_type_id'], $invoice->identification_type_id);
        $this->assertEquals($row['identification_number'], $invoice->identification_number);
        $this->assertEquals($row['debtor_name'], $invoice->debtor_name);
        $this->assertEquals($row['email'], $invoice->email);
        $this->assertEquals($row['description'], $invoice->description);
        $this->assertEquals($row['currency_id'], $invoice->currency_id);
        $this->assertEquals($row['amount'], $invoice->amount);
        $this->assertEquals(Carbon::parse($row['expiration_date']), $invoice->expiration_date);
        $this->assertEquals($userId, $invoice->user_id);
    }

    public function test_import_logs_information()
    {
        $row = [
            'microsite_id' => 1,
            'order_number' => '123456',
            'identification_type_id' => 1,
            'identification_number' => '123456789',
            'debtor_name' => 'John Doe',
            'email' => 'john@example.com',
            'description' => 'Test Invoice',
            'currency_id' => 1,
            'amount' => 100.00,
            'expiration_date' => now()->addDays(30)->format('Y-m-d'),
            'surcharge_date' => now()->addDays(20)->format('Y-m-d'),
            'surcharge_rate' => SurchargeRate::PERCENT->value,
            'percent' => 10,
            'additional_amount' => 1000,
        ];

        $userId = 1;

        Log::shouldReceive('info')->twice();

        $import = new InvoicesImport($userId);

        $import->model($row);

        Log::shouldHaveReceived('info')
            ->with('User ID in InvoicesImport:', ['user_id' => $userId]);

        Log::shouldHaveReceived('info')
            ->with('Importing row:', $row);
    }

    public function test_import_validation_rules()
    {
        $import = new InvoicesImport(1);

        $rules = $import->rules();

        $this->assertArrayHasKey('microsite_id', $rules);
        $this->assertArrayHasKey('order_number', $rules);
        $this->assertArrayHasKey('identification_type_id', $rules);
        $this->assertArrayHasKey('identification_number', $rules);
        $this->assertArrayHasKey('debtor_name', $rules);
        $this->assertArrayHasKey('email', $rules);
        $this->assertArrayHasKey('description', $rules);
        $this->assertArrayHasKey('currency_id', $rules);
        $this->assertArrayHasKey('amount', $rules);
        $this->assertArrayHasKey('expiration_date', $rules);
    }
}
