<?php namespace Bedard\Shop\Tests\Unit\Models;

use Bedard\Shop\Tests\Factory;
use Bedard\Shop\Models\Address;
use Bedard\Shop\Tests\PluginTestCase;

class AddressTest extends PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

    public function test_only_one_shipping_address_may_be_primary()
    {
        $ship1 = Factory::create(new Address, ['customer_id' => 1, 'is_shipping' => true, 'is_primary' => true]);
        $ship2 = Factory::create(new Address, ['customer_id' => 1, 'is_shipping' => true, 'is_primary' => true]);

        $this->assertFalse(Address::find($ship1->id)->is_primary);
        $this->assertTrue(Address::find($ship2->id)->is_primary);
    }

    public function test_only_one_billing_address_may_be_primary()
    {
        $bill1 = Factory::create(new Address, ['customer_id' => 1, 'is_billing' => true, 'is_primary' => true]);
        $bill2 = Factory::create(new Address, ['customer_id' => 1, 'is_billing' => true, 'is_primary' => true]);

        $this->assertFalse(Address::find($bill1->id)->is_primary);
        $this->assertTrue(Address::find($bill2->id)->is_primary);
    }
}
