<?php namespace Bedard\Shop\Interfaces;

interface ShippingInterface
{
    /**
     * Calculate the shipping for a cart.
     *
     * @param  Cart   $cart
     * @return float       
     */
    public function calculate(Cart $cart)
}
