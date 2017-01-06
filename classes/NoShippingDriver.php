<?php namespace Bedard\Shop\Classes;

use Bedard\Shop\Models\Cart;
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
}
