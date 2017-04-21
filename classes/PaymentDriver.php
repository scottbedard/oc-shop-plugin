<?php namespace Bedard\Shop\Classes;

use Bedard\Shop\Models\Cart;

abstract class PaymentDriver extends Driver
{
    abstract public function process(Cart $cart);
}
