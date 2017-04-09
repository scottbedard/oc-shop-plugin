<?php namespace Bedard\Shop\Tests\Unit\Repositories;

use Bedard\Shop\Models\Cart;
use Bedard\Shop\Repositories\CartRepository;
use PluginTestCase;
use Session;

class CartRepositoryTest extends PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

    public function test_find_returns_null_when_no_cart_is_found()
    {
        $repository = new CartRepository;
        Session::forget('bedard_shop_cart');

        $this->assertEquals(null, $repository->find());
    }

    public function test_creating_a_cart()
    {
        $repository = new CartRepository;
        $repository->create();

        $this->assertEquals(1, Cart::count());
    }

    public function test_finding_a_cart()
    {
        $repository = new CartRepository;

        $foo = $repository->create();
        $bar = $repository->find();

        $this->assertEquals($foo->id, $bar->id);
    }

    public function test_finding_or_creating_a_cart()
    {
        $repository = new CartRepository;

        $foo = $repository->findOrCreate();
        $bar = $repository->findOrCreate();

        $this->assertEquals($foo->id, $bar->id);
    }
}
