<?php

namespace App\Http\Requests;

use App\Constants\SurchargeRate;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class InvoiceCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'order_number' => ['required', 'string','max:32', 'unique:invoices,order_number'],
            'debtor_name' => ['required', 'string', 'min:4', 'max:255'],
            'microsite_id' => ['required', 'integer','exists:microsites,id'],
            'identification_type_id' => ['required', 'integer', 'exists:buyer_id_types,id'],
            'identification_number' => ['required', 'string', 'min:4', 'max:20'],
            'email' => ['required', 'email:filter,rfc,dns,spoof', 'max:120'],
            'description' => ['required', 'string', 'max:500'],
            'amount' => ['required', 'numeric', 'between:0,9999999999.99'],
            'currency_id' => ['required', 'exists:currencies,id'],
            'expiration_date' => ['required', 'date', 'after:today'],
            'surcharge_date' => ['required', 'date', 'before:expiration_date'],
            'surcharge_rate' => ['required', Rule::in(SurchargeRate::toArray())],
            'percent' => ['nullable', 'numeric', 'between:0,101'],
            'additional_amount' => ['nullable', 'numeric', 'between:1000,10000000'],
        ];
    }
}
