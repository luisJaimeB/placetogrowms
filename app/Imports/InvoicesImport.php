<?php

namespace App\Imports;

use App\Constants\InvoicesStatus;
use App\Models\Invoice;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class InvoicesImport implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;

    protected int $userId;

    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        Log::info('User ID in InvoicesImport:', ['user_id' => $this->userId]);

        Log::info('Importing row:', $row);

        return new Invoice([
            'microsite_id' => $row['microsite_id'],
            'status' => InvoicesStatus::active,
            'order_number' => $row['order_number'],
            'identification_type_id' => $row['identification_type_id'],
            'identification_number' => $row['identification_number'],
            'debtor_name' => $row['debtor_name'],
            'email' => $row['email'],
            'description' => $row['description'],
            'currency_id' => $row['currency_id'],
            'amount' => $row['amount'],
            'expiration_date' => $row['expiration_date'],
            'user_id' => $this->userId,
        ]);
    }

    public function rules(): array
    {
        return [
            'microsite_id' => ['required', 'integer', 'exists:microsites,id'],
            'order_number' => ['required', 'string', 'max:32'],
            'identification_type_id' => ['required', 'integer', 'exists:buyer_id_types,id'],
            'identification_number' => ['required', 'numeric'],
            'debtor_name' => ['required', 'string', 'min:4', 'max:255'],
            'email' => ['required', 'email', 'max:120'],
            'description' => ['required', 'string', 'max:500'],
            'currency_id' => ['required', 'exists:currencies,id'],
            'amount' => ['required', 'numeric', 'between:0,9999999999.99'],
            'expiration_date' => ['required', 'date', 'after:today'],
        ];
    }
}
