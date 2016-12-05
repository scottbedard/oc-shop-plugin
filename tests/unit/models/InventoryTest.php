<?php namespace Bedard\Shop\Tests\Unit\Models;

use Bedard\Shop\Models\Option;
use Bedard\Shop\Tests\Factory;
use Bedard\Shop\Models\Product;
use Bedard\Shop\Models\Inventory;
use Bedard\Shop\Models\OptionValue;
use Bedard\Shop\Tests\PluginTestCase;

class InventoryTest extends PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

    public function test_hasOptionValueIds_scope()
    {
        $product = Factory::create(new Product);
        $option = Factory::create(new Option, ['product_id' => $product->id]);
        $optionValue = Factory::create(new OptionValue, ['option_id' => $option->id]);
        $inventory1 = Factory::create(new Inventory);
        $inventory2 = Factory::create(new Inventory);
        $inventory2->optionValues()->sync([$optionValue->id]);

        $this->assertEquals(1, Inventory::hasOptionValueIds($optionValue->id)->count());
        $this->assertEquals($inventory2->id, Inventory::hasOptionValueIds($optionValue->id)->first()->id);
    }
}
