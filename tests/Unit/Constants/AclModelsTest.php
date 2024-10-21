<?php

namespace Tests\Unit\Constants;

use App\Constants\AclModels;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AclModelsTest extends TestCase
{
    #[Test]
    public function it_returns_correct_enum_values()
    {
        $this->assertEquals('microsites', AclModels::Microsites->value);
        $this->assertEquals('invoices', AclModels::Invoices->value);
    }

    #[Test]
    public function it_returns_correct_options()
    {
        $expectedOptions = [
            [
                'value' => 'microsites',
                'text' => 'Microsites',
            ],
            [
                'value' => 'invoices',
                'text' => 'Invoices',
            ],
        ];

        $this->assertEquals($expectedOptions, AclModels::toOptions());
    }

    #[Test]
    public function it_returns_correct_columns_for_each_model()
    {
        $this->assertEquals(['id', 'name'], AclModels::Microsites->columns());
        $this->assertEquals(['id', 'order_number'], AclModels::Invoices->columns());
    }

    #[Test]
    public function it_returns_correct_column_aliases_for_each_model()
    {
        $this->assertEquals(['name' => 'text'], AclModels::Microsites->columAliases());
        $this->assertEquals(['order_number' => 'text'], AclModels::Invoices->columAliases());
    }

    #[Test]
    public function it_applies_column_aliases_correctly()
    {
        $micrositesData = ['id' => 1, 'name' => 'Test Microsite'];
        $expectedMicrosites = ['id' => 1, 'text' => 'Test Microsite'];
        $this->assertEquals($expectedMicrosites, AclModels::Microsites->applyColumnAliases($micrositesData));

        $invoicesData = ['id' => 2, 'order_number' => 'INV-1001'];
        $expectedInvoices = ['id' => 2, 'text' => 'INV-1001'];
        $this->assertEquals($expectedInvoices, AclModels::Invoices->applyColumnAliases($invoicesData));
    }
}
