<?php

namespace App\Http\Requests;

use App\Constants\Periodicity;
use App\Constants\SubscriptionTerm;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

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
            'periodicity' => ['required', Rule::in(Periodicity::toArray())],
            'amount' => ['required', 'numeric', 'between:0,9999999999.99'],
            'subscriptionTerm' => ['required', 'string', Rule::in(SubscriptionTerm::toArray())],
            'microsite_id' => ['required', 'exists:microsites,id'],
            'items' => ['nullable', 'array'],
            'lapse' => ['required', 'numeric', 'between:6,24'],
            'attempts' => ['required', 'numeric', 'between:1,3'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function ($validator) {
            $data = $this->validated();

            $periodicityOrder = $this->getPeriodicityOrder();
            $subscriptionTermOrder = $this->getSubscriptionTermOrder();

            if ($periodicityOrder[$data['periodicity']] > $subscriptionTermOrder[$data['subscriptionTerm']]) {
                $validator->errors()->add('subscriptionTerm', 'El tiempo de suscripción no puede ser menor que la periodicidad de cobro.');
            }
        });
    }

    private static function getPeriodicityOrder(): array
    {
        return [
            Periodicity::Daily->value => 1,
            Periodicity::Biweekly->value => 2,
            Periodicity::Monthly->value => 3,
            Periodicity::Quarterly->value => 4,
            Periodicity::Semester->value => 5,
            Periodicity::Annual->value => 6,
        ];
    }

    private static function getSubscriptionTermOrder(): array
    {
        return [
            'monthly' => 3,
            'trimester' => 4,
            'semester' => 5,
            'annual' => 7,
        ];
    }
}
