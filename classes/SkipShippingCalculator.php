<?php namespace Bedard\Shop\Classes;

use Bedard\Shop\Interfaces\ShippingInterface;

class SkipShippingCalculator implements ShippingInterface
{
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
