<?php

namespace App\Http\Requests;

use App\Constants\AclActions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AclCreateRequest extends FormRequest
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
            'user_id' => ['required', 'exists:users,id'],
            'model_type' => ['required', 'string'],
            'model_id' => ['required'],
            'status' => ['required', Rule::enum(AclActions::class)],
        ];
    }
}
