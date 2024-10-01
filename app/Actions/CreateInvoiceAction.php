<?php

namespace App\Actions;

use App\Constants\AclActions;
use App\Constants\InvoicesStatus;
use App\Contracts\Create;
use App\Models\Acl;
use App\Models\Invoice;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CreateInvoiceAction implements Create
{
    public static function execute(array $data): Model|false
    {
        $invoice = new Invoice;
        $invoice->status = InvoicesStatus::active;
        $invoice->order_number = $data['order_number'];
        $invoice->identification_type_id = $data['identification_type_id'];
        $invoice->identification_number = $data['identification_number'];
        $invoice->debtor_name = $data['debtor_name'];
        $invoice->email = $data['email'];
        $invoice->description = $data['description'];
        $invoice->currency_id = $data['currency_id'];
        $invoice->amount = $data['amount'];
        $invoice->expiration_date = $data['expiration_date'];
        $invoice->microsite_id = $data['microsite_id'];
        $invoice->user_id = Auth::id();
        $invoice->save();

        $acl = new Acl;
        $acl->user_id = $invoice->user_id;
        $acl->status = AclActions::allowed;
        $acl->model()->associate($invoice);
        $acl->save();

        return $invoice;
    }
}
