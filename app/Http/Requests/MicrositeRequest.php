<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MicrositeRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'type_site_id' => 'required|exists:type_sites,id',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'expiration' => 'required|integer',
            'currency' => 'required|exists:currencies,id',
        ];
    }
}
