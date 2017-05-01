<?php namespace Bedard\Shop\Tests\Unit\Models;

use Bedard\Shop\Classes\Factory;
use Bedard\Shop\Models\CartItem;
use Bedard\Shop\Models\Inventory;
use Bedard\Shop\Tests\Unit\ShopTestCase;

class CartItemTest extends ShopTestCase
{
    public function test_reducing_an_inventory()
    {
        $inventory = Factory::create(new Inventory, ['quantity' => 10]);

        $item = Factory::create(new CartItem, [
            'inventory_id' => $inventory->id,
            'quantity' => 3,
        ]);

        $item->reduceAvailableInventory();
        $this->assertEquals(7, Inventory::find($inventory->id)->quantity);
        $this->assertTrue(CartItem::find($item->id)->is_reduced);
    }

    public function test_reducing_an_inventory_when_already_reduced_does_nothing()
    {
        $inventory = Factory::create(new Inventory, ['quantity' => 10]);

        $item = Factory::create(new CartItem, [
            'inventory_id' => $inventory->id,
            'quantity' => 3,
            'is_reduced' => true,
        ]);

        $item->reduceAvailableInventory();
        $this->assertEquals(10, Inventory::find($inventory->id)->quantity);
        $this->assertTrue(CartItem::find($item->id)->is_reduced);
    }
}
