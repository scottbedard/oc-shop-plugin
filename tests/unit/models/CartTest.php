<?php namespace Bedard\Shop\Tests\Unit\Models;

use Bedard\Shop\Models\Cart;
use PluginTestCase;

class CartTest extends PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

    public function test_creating_a_cart_generates_a_token()
    {
        $cart = Cart::create();

        $this->assertEquals(40, strlen($cart->token));
    }
}
