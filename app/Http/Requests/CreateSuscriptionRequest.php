<?php

namespace App\Http\Requests;

use App\Constants\Periodicities;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateSuscriptionRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:4', 'max:120'],
            'periodicity' => ['required', Rule::in(Periodicities::toArray())],
            'interval' => ['required', 'string'],
            'amount' => ['required', 'numeric', 'between:0,9999999999.99', ],
            'next_payment' => ['required', 'date', 'before:due_date'],
            'due_date' => ['required', 'date', 'after:next_payment'],
            'microsite_id' => ['required', 'exists:microsites,id'],
            'items' => ['nullable', 'array'],
        ];
    }
}
