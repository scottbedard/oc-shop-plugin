<?php namespace Bedard\Shop\Tet\Unit\Repositories;

use Bedard\Shop\Tests\Factory;
use Bedard\Shop\Models\Product;
use Bedard\Shop\Models\Inventory;
use Bedard\Shop\Tests\PluginTestCase;
use Bedard\Shop\Repositories\CartRepository;

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
        $cart = $repository->getCart();

        $this->assertEquals('Bedard\Shop\Models\Cart', get_class($cart));
    }

    public function test_finding_a_cart_that_already_exists()
    {
        $repository = new CartRepository;
        $original = $repository->create();
        $found = $repository->getCart();

        $this->assertEquals($original->id, $found->id);
    }

    public function test_adding_an_item_to_the_cart()
    {
        $product = Factory::create(new Product);
        $inventory = Factory::create(new Inventory, ['product_id' => $product->id, 'quantity' => 1]);

        $repository = new CartRepository;
        $repository->addInventory($inventory->id, 1);
        $cart = $repository->getCart();
        $this->assertEquals(1, $cart->items()->count());
    }

    public function test_adding_items_that_exceed_the_available_quantity()
    {
        $product = Factory::create(new Product);
        $inventory = Factory::create(new Inventory, ['product_id' => $product->id, 'quantity' => 5]);

        $repository = new CartRepository;
        $repository->addInventory($inventory->id, 10);
        $cart = $repository->getCart();
        $this->assertEquals(5, $cart->items()->first()->quantity);

        $repository->addInventory($inventory->id, 10);
        $cart = $repository->getCart();
        $this->assertEquals(5, $cart->items()->first()->quantity);
        $this->assertEquals(1, $cart->items()->count());
    }

    public function test_updating_an_item_in_the_cart()
    {
        $product = Factory::create(new Product);
        $inventory = Factory::create(new Inventory, ['product_id' => $product->id, 'quantity' => 5]);

        $repository = new CartRepository;
        $repository->addInventory($inventory->id, 10);
        $repository->setInventory($inventory->id, 3);
        $cart = $repository->getCart();

        $this->assertEquals(3, $cart->items()->first()->quantity);
    }

    public function test_deleting_an_item_from_the_cart()
    {
        $product = Factory::create(new Product);
        $inventory = Factory::create(new Inventory, ['product_id' => $product->id, 'quantity' => 5]);

        $repository = new CartRepository;
        $repository->addInventory($inventory->id, 10);
        $repository->deleteItem($inventory->id);
        $cart = $repository->getCart();

        $this->assertEquals(0, $cart->items()->count());
    }

    public function test_deleting_an_item_by_setting_its_quantity_to_zero()
    {
        $product = Factory::create(new Product);
        $inventory = Factory::create(new Inventory, ['product_id' => $product->id, 'quantity' => 5]);

        $repository = new CartRepository;
        $repository->addInventory($inventory->id, 10);
        $repository->setInventory($inventory->id, 0);
        $cart = $repository->getCart();

        $this->assertEquals(0, $cart->items()->count());
    }

    public function test_updating_multiple_inventories()
    {
        $product1 = Factory::create(new Product);
        $product2 = Factory::create(new Product);
        $inventory1 = Factory::create(new Inventory, ['product_id' => $product1->id, 'quantity' => 5]);
        $inventory2 = Factory::create(new Inventory, ['product_id' => $product2->id, 'quantity' => 5]);

        $repository = new CartRepository;
        $repository->addInventory($inventory1->id, 1);
        $repository->addInventory($inventory2->id, 1);

        $repository->updateInventories([
            $inventory1->id => 2,
            $inventory2->id => 3,
        ]);

        $cart = $repository->getCart();
        $this->assertEquals(2, $cart->items()->whereInventoryId($inventory1->id)->first()->quantity);
        $this->assertEquals(3, $cart->items()->whereInventoryId($inventory2->id)->first()->quantity);
    }

    public function test_getting_cart_with_related_data()
    {
        $repository = new CartRepository;
        $cart = $repository->loadCart();

        $this->assertEquals('Bedard\Shop\Models\Cart', get_class($cart));
        $this->assertEquals('October\Rain\Database\Collection', get_class($cart->items));
    }
}
