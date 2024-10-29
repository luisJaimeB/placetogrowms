<?php

namespace App\Http\Controllers;

use App\Actions\CreateInvoiceAction;
use App\Constants\InvoicesStatus;
use App\Constants\SurchargeRate;
use App\Http\Requests\InvoiceCreateRequest;
use App\Http\Requests\InvoiceUpdateRequest;
use App\Models\BuyerIdType;
use App\Models\Invoice;
use App\Models\Microsite;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Response;

class InvoiceController extends Controller
{
    public function index(): Response
    {
        $invoices = Invoice::where('user_id', Auth::id())
            ->with(['microsite', 'currency'])
            ->get();

        return inertia('Invoices/Index', [
            'invoices' => $invoices,
        ]);
    }

    public function create(): Response
    {
        $surchargeRates = SurchargeRate::toArray();
        $microsites = Microsite::with(['currencies'])->get();
        $identification_types = BuyerIdType::all();

        return Inertia('Invoices/Create', [
            'microsites' => $microsites,
            'identification_types' => $identification_types,
            'surchargeRates' => $surchargeRates,
        ]);
    }

    public function store(InvoiceCreateRequest $request): RedirectResponse
    {
        Log::info('Invoice data request:', $request->validated());
        CreateInvoiceAction::execute($request->validated());

        return redirect()->route('invoices.index');
    }

    public function edit(Invoice $invoice): Response
    {
        $identification_types = BuyerIdType::all();

        return inertia('Invoices/Edit', [
            'invoice' => $invoice,
            'identification_types' => $identification_types,
        ]);
    }

    public function update(InvoiceUpdateRequest $request, Invoice $invoice): RedirectResponse
    {
        if ($invoice->status != InvoicesStatus::paid) {
            $invoice->update($request->validated());
        }

        return redirect()->route('invoices.index');
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        if ($invoice->status !== InvoicesStatus::paid) {
            $invoice->delete();
        }

        return redirect()->route('invoices.index');
    }
}
