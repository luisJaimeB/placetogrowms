<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class InvoiceUpdateRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'order_number' => 'required|string|max:32',
            'debtor_name' => 'required|string|min:4|max:255',
            'identification_type_id' => 'required|integer|exists:buyer_id_types,id',
            'identification_number' => 'required|string|min:4|max:20',
            'email' => 'required|email:filter,rfc,dns,spoof|max:120',
            'description' => 'required|string|max:500',
            'amount' => 'required|numeric|between:0,9999999999.99',
            'expiration_date' => 'required|date|after:today',
        ];
    }
}
