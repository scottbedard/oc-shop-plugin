<?php namespace Bedard\Shop\Tests\Unit\Models;

use Bedard\Shop\Classes\Factory;
use Bedard\Shop\Classes\PaymentDriver;
use Bedard\Shop\Models\Cart;
use Bedard\Shop\Models\Inventory;
use Bedard\Shop\Models\Product;
use Bedard\Shop\Models\Status;
use Carbon\Carbon;
use PluginTestCase;

class TestDriver extends PaymentDriver
{
}

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

    public function test_open_and_closed_scopes()
    {
        $open = Factory::create(new Cart);
        $closed = Factory::create(new Cart, ['closed_at' => Carbon::now()]);

        $this->assertEquals(1, Cart::isOpen()->count());
        $this->assertEquals(1, Cart::isClosed()->count());
        $this->assertEquals($open->id, Cart::isOpen()->first()->id);
        $this->assertEquals($closed->id, Cart::isClosed()->first()->id);
    }

    public function test_closing_a_cart_sets_the_closed_fields()
    {
        $driver = new TestDriver;
        $cart = Factory::create(new Cart);

        $this->assertNull($cart->closed_by);
        $this->assertNull($cart->closed_at);

        $cart->finalize($driver);

        $cart = Cart::find($cart->id);
        $this->assertEquals(Carbon::now(), $cart->closed_at);
        $this->assertEquals(get_class($driver), $cart->closed_by);
    }

    public function test_closing_a_cart_reduces_the_available_inventory()
    {
        $product = Factory::create(new Product, ['base_price' => 0.5]);
        $inventory = Factory::create(new Inventory, ['product_id' => $product->id, 'quantity' => 5]);
        $cart = Factory::create(new Cart);

        $cart->addInventory($inventory->id, 2);
        $cart->finalize(new TestDriver);

        $inventory = Inventory::find($inventory->id);
        $this->assertEquals(3, $inventory->quantity);
    }

    public function test_restocking_a_cart()
    {
        $product = Factory::create(new Product, ['base_price' => 0.5]);
        $inventory = Factory::create(new Inventory, ['product_id' => $product->id, 'quantity' => 5]);
        $cart = Factory::create(new Cart);

        $cart->addInventory($inventory->id, 2);
        $cart->restockInventories();

        $inventory = Inventory::find($inventory->id);
        $this->assertEquals(7, $inventory->quantity);
    }
}
