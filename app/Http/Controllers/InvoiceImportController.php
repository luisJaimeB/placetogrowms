<?php

namespace App\Http\Controllers;

use App\Http\Requests\ImportInvoiceRequest;
use App\Jobs\ImportInvoicesJob;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Response;

class InvoiceImportController extends Controller
{
    public function create(): Response
    {
        return inertia('Imports/Create');
    }

    public function import(ImportInvoiceRequest $request): RedirectResponse
    {
        $data = $request->validated();
        Log::info('Import data request:', $data);

        try {
            $filePath = $request->file('file')->store('temp');

            $userId = Auth::id();
            Log::info('User ID in controller:', ['user_id' => $userId]);

            ImportInvoicesJob::dispatch($filePath, $userId);
            Log::info('Invoices import job dispatched successfully');

            return redirect()->back()->with('success', 'Invoices import dispatched successfully!');
        } catch (\Exception $e) {
            Log::error('Error dispatching import job: '.$e->getMessage());

            return redirect()->back()->withErrors(['file' => $e->getMessage()]);
        }
    }
}
