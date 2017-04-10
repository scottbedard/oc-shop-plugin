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

    public function test_adding_an_item_syncs_the_cart()
    {
        $product = Factory::create(new Product, ['base_price' => 0.5]);
        $inventory = Factory::create(new Inventory, ['product_id' => $product->id, 'quantity' => 5]);
        $cart = Factory::create(new Cart);

        $cart->addInventory($inventory->id, 3);
        $cart = Cart::find($cart->id);

        $this->assertEquals(3, $cart->item_count);
        $this->assertEquals(1.5, $cart->item_total);
    }

    public function test_adding_more_than_available_inventory()
    {
        $product = Factory::create(new Product, ['base_price' => 1]);
        $inventory = Factory::create(new Inventory, ['product_id' => $product->id, 'quantity' => 5]);
        $cart = Factory::create(new Cart);

        $cart->addInventory($inventory->id, 10);
        $cart = Cart::find($cart->id);
        $this->assertEquals(5, $cart->items()->whereInventoryId($inventory->id)->first()->quantity);
    }

    public function test_setting_an_inventory()
    {
        $product = Factory::create(new Product, ['base_price' => 0.5]);
        $inventory = Factory::create(new Inventory, ['product_id' => $product->id, 'quantity' => 5]);
        $cart = Factory::create(new Cart);

        $cart->addInventory($inventory->id, 3);
        $cart->setInventory($inventory->id, 1);

        $cart = Cart::find($cart->id);
        $this->assertEquals(1, $cart->item_count);
        $this->assertEquals(0.5, $cart->item_total);
        $this->assertEquals(1, $cart->items()->whereInventoryId($inventory->id)->first()->quantity);
    }

    public function test_deleting_an_inventory()
    {
        $product = Factory::create(new Product, ['base_price' => 0.5]);
        $inventory = Factory::create(new Inventory, ['product_id' => $product->id, 'quantity' => 5]);
        $cart = Factory::create(new Cart);

        $cart->addInventory($inventory->id, 3);
        $cart->deleteInventory($inventory->id);

        $this->assertEquals(0, $cart->item_count);
        $this->assertEquals(0, $cart->item_total);
        $this->assertEquals(0, $cart->items->count());
    }
}
