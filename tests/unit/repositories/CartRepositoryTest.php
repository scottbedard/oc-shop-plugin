<?php namespace Bedard\Shop\Tet\Unit\Repositories;

use Bedard\Shop\Repositories\CartRepository;
use Bedard\Shop\Tests\PluginTestCase;

class CartRepositoryTest extends PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

    public function test_creating_a_cart()
    {
        $repository = new CartRepository;
        $cart = $repository->create();

        $this->assertEquals('Bedard\Shop\Models\Cart', get_class($cart));
    }

    public function test_finding_a_cart_when_none_exists()
    {
        $repository = new CartRepository;
        $cart = $repository->find();

        $this->assertEquals('Bedard\Shop\Models\Cart', get_class($cart));
    }

    public function test_finding_a_cart_that_already_exists()
    {
        $repository = new CartRepository;
        $original = $repository->create();
        $found = $repository->find();

        $this->assertEquals($original->id, $found->id);
    }
}
