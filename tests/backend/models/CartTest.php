<?php namespace Bedard\Shop\Tests\Backend\Models;

use Bedard\Shop\Classes\Factory;
use Bedard\Shop\Classes\PaymentDriver;
use Bedard\Shop\Models\Cart;
use Bedard\Shop\Models\Inventory;
use Bedard\Shop\Models\Product;
use Bedard\Shop\Models\Settings;
use Bedard\Shop\Models\Status;
use Bedard\Shop\Tests\Backend\ShopTestCase;
use Carbon\Carbon;

class TestDriver extends PaymentDriver
{
}

class CartTest extends ShopTestCase
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

    public function test_is_abandoned_scopes()
    {
        Settings::set('cart_lifespan', 10);

        $open = Factory::create(new Cart);
        $abandoned = Factory::create(new Cart);

        Cart::whereId($abandoned->id)->update([
            'created_at' => Carbon::now()->subMinutes(20),
            'updated_at' => Carbon::now()->subMinutes(11),
        ]);

        $this->assertEquals(1, Cart::isAbandoned()->count());
        $this->assertEquals($abandoned->id, Cart::isAbandoned()->first()->id);
    }

    public function test_closing_a_cart_sets_the_closed_fields()
    {
        $driver = new TestDriver;
        $cart = Factory::create(new Cart);

        $this->assertNull($cart->closed_at);

        $cart->close($driver);

        $cart = Cart::find($cart->id);
        $this->assertEquals(Carbon::now(), $cart->closed_at);
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

    public function test_abandoning_a_cart()
    {
        $cart = Factory::create(new Cart);
        $status = Factory::create(new Status, ['is_abandoned' => true]);

        $cart->abandon();

        $this->assertEquals(Carbon::now(), $cart->abandoned_at);
        $this->assertEquals(1, $cart->statuses()->whereId($status->id)->count());
    }

    public function test_setting_a_status_without_a_driver()
    {
        $cart = Factory::create(new Cart);
        $status = Factory::create(new Status);

        $cart->setStatus($status);
        $cart->load('statuses');
        $data = $cart->toArray();

        $this->assertEquals(null, $data['statuses'][1]['pivot']['driver']);
    }

    public function test_setting_a_status_with_a_driver()
    {
        $cart = Factory::create(new Cart);
        $status = Factory::create(new Status);
        $driver = new TestDriver;

        $cart->setStatus($status, $driver);
        $cart->load('statuses');
        $data = $cart->toArray();

        $this->assertEquals(get_class($driver), $data['statuses'][1]['pivot']['driver']);
    }

    public function test_processing_abandoned_carts()
    {
        // set the cart lifespan to 10 minutes
        Settings::set('cart_lifespan', 10);

        // create a cart was last updated 20 minutes ago
        $cart = Factory::create(new Cart);
        $cart->updated_at = Carbon::now()->subMinutes(20);
        $cart->save();

        // process the abandoned carts
        Cart::processAbandoned();

        // our cart should now be abandoned
        $cart = Cart::find($cart->id);
        $this->assertEquals(Carbon::now(), $cart->abandoned_at);
    }

    public function test_attaching_a_reducing_status()
    {
        // create a product, inventory, and cart
        $product = Factory::create(new Product);
        $inventory = Factory::create(new Inventory, ['product_id' => $product->id, 'quantity' => 5]);
        $cart = Factory::create(new Cart);

        // create a status that is reducing
        $status = Factory::create(new Status, ['is_reducing' => true]);

        // add our inventory to the cart and refresh it
        $cart->addInventory($inventory->id, 3);
        $cart = Cart::with('items.inventory')->find($cart->id);

        // set the cart's status
        $cart->setStatus($status);
        $this->assertEquals(2, Inventory::find($inventory->id)->quantity);
    }
}
