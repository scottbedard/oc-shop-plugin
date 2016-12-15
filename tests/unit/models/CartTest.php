<?php namespace Bedard\Shop\Tests\Unit\Models;

use Bedard\Shop\Models\Cart;
use Bedard\Shop\Models\Promotion;
use Bedard\Shop\Tests\Factory;
use Bedard\Shop\Tests\PluginTestCase;
use Carbon\Carbon;

class CartTest extends PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

    public function test_creating_a_cart_with_a_unique_token()
    {
        $cart = Factory::create(new Cart);
        $this->assertEquals(40, strlen($cart->token));
    }

    public function test_applying_an_active_promotion()
    {
        $cart = Factory::create(new Cart);
        $promotion = Factory::create(new Promotion);

        $cart->applyPromotion($promotion->name);
        $this->assertEquals($promotion->id, $cart->promotion_id);
    }

    public function test_applying_an_inactive_promotion()
    {
        $cart = Factory::create(new Cart);
        $promotion = Factory::create(new Promotion, ['end_at' => Carbon::yesterday()]);

        $this->setExpectedException(\Illuminate\Database\Eloquent\ModelNotFoundException::class);
        $cart->applyPromotion($promotion->name);
    }
}
