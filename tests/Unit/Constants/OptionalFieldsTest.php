<?php

namespace Tests\Unit\Constants;

use App\Constants\OptionalFields;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OptionalFieldsTest extends TestCase
{
    #[Test]
    public function it_has_correct_address_field()
    {
        $expected = [
            'label' => 'address',
            'field' => 'address',
            'rule' => 'required|regex:/^\d+\s[A-Za-z0-9\s,\'\-\.#]+$/',
        ];

        $this->assertEquals($expected, OptionalFields::ADDRESS);
    }

    #[Test]
    public function it_has_correct_city_field()
    {
        $expected = [
            'label' => 'city',
            'field' => 'city',
            'rule' => 'required|string|max:255',
        ];

        $this->assertEquals($expected, OptionalFields::CITY);
    }

    #[Test]
    public function it_has_correct_country_field()
    {
        $expected = [
            'label' => 'country',
            'field' => 'country',
            'rule' => 'required|string|max:255',
        ];

        $this->assertEquals($expected, OptionalFields::COUNTRY);
    }

    #[Test]
    public function it_returns_all_fields_as_array()
    {
        $expected = [
            'ADDRESS' => [
                'label' => 'address',
                'field' => 'address',
                'rule' => 'required|regex:/^\d+\s[A-Za-z0-9\s,\'\-\.#]+$/',
            ],
            'CITY' => [
                'label' => 'city',
                'field' => 'city',
                'rule' => 'required|string|max:255',
            ],
            'COUNTRY' => [
                'label' => 'country',
                'field' => 'country',
                'rule' => 'required|string|max:255',
            ],
        ];

        $this->assertEquals($expected, OptionalFields::toArray());
    }
}
