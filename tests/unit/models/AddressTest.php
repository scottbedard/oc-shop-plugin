<?php namespace Bedard\Shop\Tests\Unit\Models;

use Bedard\Shop\Classes\Factory;
use Bedard\Shop\Models\Address;
use Bedard\Shop\Tests\Unit\ShopTestCase;
use RainLab\User\Models\User;

class AddressTest extends ShopTestCase
{
    public function test_initial_street_value_is_an_empty_array()
    {
        $address = new Address;
        $this->assertTrue(is_array($address->street));
        $this->assertEquals(0, count($address->street));
    }

    public function test_saving_multiple_street_lines()
    {
        $address = Factory::create(new Address, [
            'street' => [
                '123 Foo St.',
                'Apartment #1234',
            ],
        ]);

        $this->assertEquals('123 Foo St.', $address->street[0]);
        $this->assertEquals('Apartment #1234', $address->street[1]);
    }
}
