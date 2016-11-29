<?php namespace Bedard\Shop\Tet\Unit\Repositories;

use Bedard\Shop\Models\Inventory;
use Bedard\Shop\Models\Product;
use Bedard\Shop\Repositories\CartRepository;
use Bedard\Shop\Tests\Factory;
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

    public function test_adding_an_item_to_the_cart()
    {
        $product = Factory::create(new Product);
        $inventory = Factory::create(new Inventory, ['product_id' => $product->id, 'quantity' => 1]);

        $repository = new CartRepository;
        $cart = $repository->add($inventory, 1);
        $this->assertEquals(1, $cart->items()->count());
    }

    public function test_adding_items_that_exceed_the_available_quantity()
    {
        $product = Factory::create(new Product);
        $inventory = Factory::create(new Inventory, ['product_id' => $product->id, 'quantity' => 5]);

        $repository = new CartRepository;
        $cart = $repository->add($inventory, 10);
        $this->assertEquals(5, $cart->items()->first()->quantity);

        $cart = $repository->add($inventory, 10);
        $this->assertEquals(5, $cart->items()->first()->quantity);
        $this->assertEquals(1, $cart->items()->count());
    }

    public function test_updating_an_item_in_the_cart()
    {
        $product = Factory::create(new Product);
        $inventory = Factory::create(new Inventory, ['product_id' => $product->id, 'quantity' => 5]);

        $repository = new CartRepository;
        $cart = $repository->add($inventory, 10);
        $repository->update($inventory, 3);

        $this->assertEquals(3, $cart->items()->first()->quantity);
    }

    public function test_deleting_an_item_from_the_cart()
    {
        $product = Factory::create(new Product);
        $inventory = Factory::create(new Inventory, ['product_id' => $product->id, 'quantity' => 5]);

        $repository = new CartRepository;
        $cart = $repository->add($inventory, 10);
        $repository->delete($inventory);

        $this->assertEquals(0, $cart->items()->count());
    }

    public function test_deleting_an_item_by_setting_its_quantity_to_zero()
    {
        $product = Factory::create(new Product);
        $inventory = Factory::create(new Inventory, ['product_id' => $product->id, 'quantity' => 5]);

        $repository = new CartRepository;
        $cart = $repository->add($inventory, 10);
        $repository->update($inventory, 0);

        $this->assertEquals(0, $cart->items()->count());
    }
}
