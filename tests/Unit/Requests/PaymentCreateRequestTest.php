<?php

namespace Tests\Unit\Requests;

use App\Http\Requests\PaymentCreateRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class PaymentCreateRequestTest extends TestCase
{
    public function test_authorization()
    {
        $request = new PaymentCreateRequest();
        $this->assertTrue($request->authorize());
    }

    public function test_validation_rules()
    {
        $request = new PaymentCreateRequest();
        $rules = $request->rules();

        // Verifica que todas las reglas de validación estén presentes
        $this->assertArrayHasKey('buyer_id_type', $rules);
        $this->assertArrayHasKey('buyer_id', $rules);
        $this->assertArrayHasKey('amount', $rules);
        $this->assertArrayHasKey('currency', $rules);
        $this->assertArrayHasKey('description', $rules);
        $this->assertArrayHasKey('phone', $rules);
        $this->assertArrayHasKey('name', $rules);
        $this->assertArrayHasKey('lastName', $rules);
        $this->assertArrayHasKey('email', $rules);
        $this->assertArrayHasKey('paymentMethod', $rules);
        $this->assertArrayHasKey('type', $rules);
        $this->assertArrayHasKey('micrositeId', $rules);
        $this->assertArrayHasKey('expiration', $rules);
        $this->assertArrayHasKey('optional_fields', $rules);
        $this->assertArrayHasKey('plan', $rules);
    }

    public function test_validation_passes_with_valid_data()
    {
        $data = [
            'buyer_id_type' => 1,
            'buyer_id' => '123456789012',
            'amount' => 100.00,
            'currency' => 1,
            'description' => 'Test payment',
            'phone' => '+1234567890',
            'name' => 'John',
            'lastName' => 'Doe',
            'email' => 'john@example.com',
            'paymentMethod' => 'credit_card',
            'type' => 1,
            'micrositeId' => 1,
            'expiration' => now()->addDays(30)->format('Y-m-d'),
            'optional_fields' => [],
            'plan' => null,
        ];

        $request = new PaymentCreateRequest();
        $validator = Validator::make($data, $request->rules());

        $this->assertTrue($validator->passes());
    }

    public function test_validation_fails_without_required_fields()
    {
        $data = []; // Sin datos

        $request = new PaymentCreateRequest();
        $validator = Validator::make($data, $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('buyer_id_type', $validator->errors()->toArray());
        $this->assertArrayHasKey('buyer_id', $validator->errors()->toArray());
        $this->assertArrayHasKey('amount', $validator->errors()->toArray());
        $this->assertArrayHasKey('currency', $validator->errors()->toArray());
        $this->assertArrayHasKey('name', $validator->errors()->toArray());
        $this->assertArrayHasKey('email', $validator->errors()->toArray());
        $this->assertArrayHasKey('paymentMethod', $validator->errors()->toArray());
        $this->assertArrayHasKey('type', $validator->errors()->toArray());
        $this->assertArrayHasKey('micrositeId', $validator->errors()->toArray());
        $this->assertArrayHasKey('expiration', $validator->errors()->toArray());
    }

    public function test_plan_field_is_required_when_type_is_3()
    {
        $data = [
            'buyer_id_type' => 1,
            'buyer_id' => '123456789012',
            'amount' => 100.00,
            'currency' => 1,
            'name' => 'John',
            'email' => 'john@example.com',
            'paymentMethod' => 'credit_card',
            'type' => 3, // Tipo de suscripción
            'micrositeId' => 1,
            'expiration' => now()->addDays(30)->format('Y-m-d'),
        ];

        $request = new PaymentCreateRequest();
        $validator = Validator::make($data, $request->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('plan', $validator->errors()->toArray());
    }

    public function test_plan_field_is_optional_when_type_is_not_3()
    {
        $data = [
            'buyer_id_type' => 1,
            'buyer_id' => '123456789012',
            'amount' => 100.00,
            'currency' => 1,
            'name' => 'John',
            'email' => 'john@example.com',
            'paymentMethod' => 'credit_card',
            'type' => 1,
            'micrositeId' => 1,
            'expiration' => now()->addDays(30)->format('Y-m-d'),
            'plan' => null,
        ];

        $request = new PaymentCreateRequest();
        $validator = Validator::make($data, $request->rules());

        $this->assertTrue($validator->passes());
    }
}
