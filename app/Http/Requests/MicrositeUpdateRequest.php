<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MicrositeUpdateRequest extends FormRequest
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
            'name' => 'sometimes|required|string|max:255',
            'category' => 'sometimes|required|exists:categories,id',
            'siteType' => 'sometimes|required|exists:type_sites,id',
            'logo' => 'nullable|max:1024',
            'expiration' => 'sometimes|required|integer',
            'currency' => 'sometimes|required|exists:currencies,id',
        ];
    }
}
