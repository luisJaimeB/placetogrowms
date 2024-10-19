<?php

namespace Constants;

use App\Constants\Optionalfields;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class OptionalfieldsTest extends TestCase
{
    #[Test]
    public function it_has_correct_address_field()
    {
        $expected = [
            'label' => 'address',
            'field' => 'address',
            'rule' => 'required|regex:/^\d+\s[A-Za-z0-9\s,\'\-\.#]+$/',
        ];

        $this->assertEquals($expected, Optionalfields::ADDRESS);
    }

    #[Test]
    public function it_has_correct_city_field()
    {
        $expected = [
            'label' => 'city',
            'field' => 'city',
            'rule' => 'required|string|max:255',
        ];

        $this->assertEquals($expected, Optionalfields::CITY);
    }

    #[Test]
    public function it_has_correct_country_field()
    {
        $expected = [
            'label' => 'country',
            'field' => 'country',
            'rule' => 'required|string|max:255',
        ];

        $this->assertEquals($expected, Optionalfields::COUNTRY);
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

        $this->assertEquals($expected, Optionalfields::toArray());
    }
}
