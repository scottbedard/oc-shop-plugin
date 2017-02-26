<?php namespace Bedard\Shop\Tests\Unit\Models;

use Bedard\Shop\Classes\Factory;
use Bedard\Shop\Models\Inventory;
use Bedard\Shop\Models\Option;
use October\Rain\Exception\ValidationException;
use PluginTestCase;

class InventoryTest extends PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

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
}
