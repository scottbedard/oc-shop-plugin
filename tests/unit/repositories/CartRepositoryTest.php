<?php namespace Bedard\Shop\Tests\Unit\Repositories;

use Bedard\Shop\Classes\Factory;
use Bedard\Shop\Models\Cart;
use Bedard\Shop\Models\CartItem;
use Bedard\Shop\Models\Inventory;
use Bedard\Shop\Models\Product;
use Bedard\Shop\Repositories\CartRepository;
use Bedard\Shop\Tests\Unit\ShopTestCase;
use Session;

class CartRepositoryTest extends ShopTestCase
{
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

    public function test_adding_an_item_to_a_cart()
    {
        $repository = new CartRepository;
        $product = Factory::create(new Product);
        $inventory = Factory::create(new Inventory, ['product_id' => $product->id, 'quantity' => 5]);

        $item = $repository->add($inventory->id, 1);
        $cart = $repository->find();
        $this->assertEquals(1, $cart->items()->first()->quantity);

        $item = $repository->add($inventory->id, 2);
        $this->assertEquals(1, $cart->items()->count());
        $this->assertEquals(3, $cart->items()->first()->quantity);
    }

    public function test_updating_an_item_in_the_cart()
    {
        $repository = new CartRepository;
        $product = Factory::create(new Product);
        $inventory = Factory::create(new Inventory, ['product_id' => $product->id, 'quantity' => 5]);

        $item = $repository->add($inventory->id, 1);
        $repository->update($inventory->id, 2);
        $cart = $repository->find();

        $this->assertEquals(2, $cart->items()->first()->quantity);
    }

    public function test_removing_an_item_from_the_cart()
    {
        $repository = new CartRepository;
        $product = Factory::create(new Product);
        $inventory = Factory::create(new Inventory, ['product_id' => $product->id, 'quantity' => 5]);

        $item = $repository->add($inventory->id, 1);
        $this->assertNull($item->deleted_at);

        $repository->remove($item->id);
        $item = CartItem::withTrashed()->find($item->id);
        $this->assertNotNull($item->deleted_at);
    }
}
