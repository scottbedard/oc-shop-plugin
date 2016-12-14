<?php namespace Bedard\Shop\Tests\Unit\Models;

use Bedard\Shop\Models\CartItem;
use Bedard\Shop\Models\Inventory;
use Bedard\Shop\Tests\Factory;
use Bedard\Shop\Tests\PluginTestCase;

class CartItemTest extends PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

    public function test_cart_item_quantities_cannot_exceed_available_inventory()
    {
        $inventory = Factory::create(new Inventory, [ 'quantity' => 5 ]);
        $item = Factory::fill(new CartItem, [ 'inventory_id' => $inventory->id, 'quantity' => 10 ]);
        $item->save();

        $this->assertEquals(5, $item->quantity);
    }

    public function test_cart_item_quantities_cannot_be_below_zero()
    {
        $inventory = Factory::create(new Inventory, [ 'quantity' => 5 ]);
        $item = Factory::fill(new CartItem, [ 'inventory_id' => $inventory->id, 'quantity' => -4 ]);
        $item->save();

        $this->assertEquals(0, $item->quantity);
    }
}
