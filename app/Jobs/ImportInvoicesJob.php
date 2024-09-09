<?php

namespace App\Jobs;

use App\Imports\InvoicesImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class ImportInvoicesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $filePath;

    protected int $userId;

    public function __construct($filePath, $userId)
    {
        $this->filePath = $filePath;
        $this->userId = $userId;
        Log::info('User ID in job constructor:', ['user_id' => $this->userId]);
    }

    public function handle(): void
    {
        Log::info('User ID in job handle:', ['user_id' => $this->userId]);
        try {
            $file = Storage::path($this->filePath);
            Log::info('User ID:', ['user_id' => $this->userId]);
            Excel::import(new InvoicesImport($this->userId), $file);
            Log::info('Invoices imported successfully');

            Storage::delete($this->filePath);
        } catch (\Exception $e) {
            Log::error('Error during import: '.$e->getMessage());
        }
    }
}
