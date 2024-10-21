<?php

namespace Tests\Unit\Constants;

use App\Constants\BuyerIdTypes;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class BuyerIdTypesTest extends TestCase
{
    #[Test]
    public function it_returns_correct_enum_values()
    {
        $this->assertEquals('CC', BuyerIdTypes::CC->value);
        $this->assertEquals('CE', BuyerIdTypes::CE->value);
        $this->assertEquals('TI', BuyerIdTypes::TI->value);
        $this->assertEquals('NIT', BuyerIdTypes::NIT->value);
        $this->assertEquals('RUT', BuyerIdTypes::RUT->value);
    }

    #[Test]
    public function it_returns_correct_document_type()
    {
        $this->assertEquals('Cédula de ciudadanía', BuyerIdTypes::CC->documentType());
        $this->assertEquals('Tarjeta de identidad', BuyerIdTypes::TI->documentType());
        $this->assertEquals('Cédula de extranjería', BuyerIdTypes::CE->documentType());
        $this->assertEquals('Número de Identificación Tributaria', BuyerIdTypes::NIT->documentType());
        $this->assertEquals('Registro único tributario', BuyerIdTypes::RUT->documentType());
    }

    #[Test]
    public function it_returns_correct_types_array()
    {
        $expectedArray = [
            [
                'code' => 'CC',
                'document_type' => 'Cédula de ciudadanía',
            ],
            [
                'code' => 'CE',
                'document_type' => 'Cédula de extranjería',
            ],
            [
                'code' => 'TI',
                'document_type' => 'Tarjeta de identidad',
            ],
            [
                'code' => 'NIT',
                'document_type' => 'Número de Identificación Tributaria',
            ],
            [
                'code' => 'RUT',
                'document_type' => 'Registro único tributario',
            ],
        ];

        $this->assertEquals($expectedArray, BuyerIdTypes::toTypes());
    }
}
