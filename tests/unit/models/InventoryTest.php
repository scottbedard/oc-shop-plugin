<?php namespace Bedard\Shop\Tests\Unit\Models;

use Bedard\Shop\Classes\Factory;
use Bedard\Shop\Models\Inventory;
use Bedard\Shop\Models\Option;
use Bedard\Shop\Models\Product;
use October\Rain\Exception\ValidationException;
use Bedard\Shop\Tests\Unit\ShopTestCase;

class InventoryTest extends ShopTestCase
{
    public function test_sku_must_be_unique()
    {
        // null sku's should not collide
        Factory::create(new Inventory, ['sku' => null]);
        Factory::create(new Inventory, ['sku' => null]);

        // defined sku's should
        Factory::create(new Inventory, ['sku' => 'foo']);
        $this->setExpectedException(ValidationException::class);
        Factory::create(new Inventory, ['sku' => 'foo']);
    }

    public function test_saving_related_inventory_values()
    {
        $option = Factory::create(new Option, [
            'value_data' => [
                ['id' => null, 'name' => 'a', 'sort_order' => 0],
                ['id' => null, 'name' => 'b', 'sort_order' => 1],
            ],
        ]);

        $inventory = Factory::create(new Inventory, ['value_ids' => [1, 2]]);
        $this->assertEquals(2, $inventory->values()->count());
    }

    public function test_finding_enabled_inventories()
    {
        $enabled = Factory::create(new Product, ['is_enabled' => true]);
        $disabled = Factory::create(new Product, ['is_enabled' => false]);
        $inv1 = Factory::create(new Inventory, ['product_id' => $enabled->id]);
        $inv2 = Factory::create(new Inventory, ['product_id' => $disabled->id]);

        $inventories = Inventory::isEnabled()->get();
        $this->assertEquals(1, count($inventories));
        $this->assertEquals($inv1->id, $inventories->first()->id);
    }
}
