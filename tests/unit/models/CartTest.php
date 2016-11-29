<?php namespace Bedard\Shop\Tests\Unit\Models;

use Bedard\Shop\Models\Cart;
use Bedard\Shop\Tests\Factory;
use Bedard\Shop\Tests\PluginTestCase;

class CartTest extends PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

    public function test_creating_a_cart_with_a_unique_token()
    {
        $cart = Factory::create(new Cart);
        $this->assertEquals(40, strlen($cart->token));
    }
}
