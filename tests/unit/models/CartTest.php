<?php namespace Bedard\Shop\Tests\Unit\Models;

use Bedard\Shop\Classes\Factory;
use Bedard\Shop\Models\Cart;
use Bedard\Shop\Models\Inventory;
use Bedard\Shop\Models\Product;
use Bedard\Shop\Models\Status;
use PluginTestCase;

class CartTest extends PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

    public function test_creating_a_cart_generates_a_token()
    {
        $cart = Cart::create();

        $this->assertEquals(40, strlen($cart->token));
    }

    public function test_saving_the_cart_updates_the_update_count()
    {
        $cart = Factory::fill(new Cart);
        $this->assertEquals(0, $cart->update_count);

        $cart->save();
        $this->assertEquals(1, $cart->update_count);

        $cart->save();
        $this->assertEquals(2, $cart->update_count);
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

    public function test_removing_an_item()
    {
        $product = Factory::create(new Product, ['base_price' => 0.5]);
        $inventory = Factory::create(new Inventory, ['product_id' => $product->id, 'quantity' => 5]);
        $cart = Factory::create(new Cart);

        $item = $cart->addInventory($inventory->id);
        $this->assertEquals(1, $cart->items()->count());
        $updateCount = $cart->update_count;

        $cart->removeItem($item->id);
        $this->assertEquals(0, $cart->items()->count());
        $this->assertEquals(1, $cart->items()->withTrashed()->count());
        $this->assertEquals($updateCount + 1, $cart->update_count);
    }

    public function test_that_the_default_status_is_created()
    {
        $status = Factory::create(new Status, ['is_default' => 1]);
        $cart = Factory::create(new Cart);

        $this->assertEquals(1, $cart->statuses()->whereId($status->id)->count());
    }
}
