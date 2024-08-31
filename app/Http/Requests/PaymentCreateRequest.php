<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class PaymentCreateRequest extends FormRequest
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
            'buyer_id_type' => 'required|exists:buyer_id_types,id',
            'buyer_id' => 'required|string|min:6|max:15',
            'amount' => 'required|numeric|between:0,9999999999.99',
            'currency' => 'required|exists:currencies,id',
            'description' => 'nullable|string|min:4|max:50',
            'phone' => 'nullable|string|regex:/^\+?[1-9]\d{1,14}$/',
            'name' => 'required|string|max:71',
            'lastName' => 'nullable|string|max:71',
            'email' => 'required|email|max:255',
            'paymentMethod' => 'required',
            'type' => 'required',
            'micrositeId' => 'required',
            'expiration' => 'required',
            'optional_fields' => 'nullable|array'
        ];
    }
}
