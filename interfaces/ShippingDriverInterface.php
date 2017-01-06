<?php namespace Bedard\Shop\Interfaces;

use Bedard\Shop\Models\Cart;

interface ShippingDriverInterface extends DriverInterface
{
    /**
     * Calculate the shipping for a cart.
     *
     * @param  Cart   $cart
     * @return float
     */
    public function calculate(Cart $cart);
}
