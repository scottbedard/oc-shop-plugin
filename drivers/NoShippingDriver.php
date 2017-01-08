<?php namespace Bedard\Shop\Drivers;

use Bedard\Shop\Models\Cart;
use October\Rain\Parse\Yaml;
use Bedard\Shop\Interfaces\ShippingDriverInterface;

class NoShippingDriver implements ShippingDriverInterface
{
    /**
     * Return details about this driver.
     *
     * @return array
     */
    public function driverDetails()
    {
        return [
            'name' => 'No shipping',
        ];
    }

    /**
     * Calculate the shipping for a cart.
     *
     * @param  Cart   $cart
     * @return float
     */
    public function calculate(Cart $cart)
    {
    }

    /**
     * Return the form fields for this driver.
     *
     * @return array
     */
    public function getFormFields()
    {
        $yaml = new Yaml;

        return $yaml->parseFile(__DIR__.'/noshipping/fields.yaml');
    }
}
