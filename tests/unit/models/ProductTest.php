<?php namespace Bedard\Shop\Tests\Unit\Models;

use Bedard\Shop\Classes\Factory;
use Bedard\Shop\Models\Category;
use Bedard\Shop\Models\Inventory;
use Bedard\Shop\Models\Option;
use Bedard\Shop\Models\Product;
use PluginTestCase;

class ProductTest extends PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

    public function test_products_can_belong_to_categories()
    {
        $category = Factory::create(new Category);
        $product = Factory::create(new Product);
        $product->categories()->attach($category);
        $this->assertEquals($category->id, $product->categories()->find($category->id)->id);
    }

    public function test_setting_plain_description()
    {
        $product = Factory::create(new Product, ['description_html' => '<b>Hello</b>']);
        $this->assertEquals('Hello', $product->description_plain);
    }

    public function test_selecting_status()
    {
        $disabled = Factory::create(new Product, ['is_enabled' => false]);
        $enabled = Factory::create(new Product, ['is_enabled' => true]);
        $products = Product::selectStatus()->get();
        $this->assertEquals(0, $products->find($disabled->id)->status);
        $this->assertEquals(1, $products->find($enabled->id)->status);
    }

    public function test_saving_options()
    {
        $option = Factory::create(new Option);
        $option->load('values');
        $optionData = $option->toArray();
        $optionData['value_data'] = $optionData['values'];
        unset($optionData['values']);

        $product = Factory::create(new Product, [
            'options_inventories' => json_encode([
                'inventories' => [],
                'options' => [$optionData],
            ]),
        ]);

        $this->assertEquals(1, $product->options()->count());
        $this->assertEquals($option->id, $product->options()->first()->id);
    }

    public function test_deleting_options()
    {
        $option = Factory::create(new Option);
        $option->load('values');
        $optionData = $option->toArray();
        $optionData['value_data'] = $optionData['values'];
        unset($optionData['values']);

        $product = Factory::create(new Product, [
            'options_inventories' => json_encode([
                'inventories' => [],
                'options' => [$optionData],
            ]),
        ]);

        $product->options_inventories = json_encode([
            'inventories' => [],
            'options' => [
                [
                    '_deleted' => true,
                    'id' => $option->id,
                    'name' => $option->name,
                    'value_data' => [['id' => null, 'name' => 'a']],
                ],
            ],
        ]);

        $product->save();
        $this->assertEquals(0, $product->options()->count());
    }

    public function test_saving_default_inventory()
    {
        $inventory = Factory::create(new Inventory);
        $inventoryData = $inventory->toArray();
        $inventoryData['value_ids'] = [];

        $product = Factory::create(new Product, [
            'options_inventories' => json_encode([
                'inventories' => [$inventoryData],
                'options' => [],
            ]),
        ]);

        $this->assertEquals(1, $product->inventories()->count());
        $this->assertEquals($inventory->id, $product->inventories()->first()->id);
    }

    public function test_is_enabled_scope()
    {
        $enabled = Factory::create(new Product, ['is_enabled' => true]);
        $disabled = Factory::create(new Product, ['is_enabled' => false]);

        $query = Product::isEnabled();
        $this->assertEquals(1, $query->count());
        $this->assertEquals($enabled->id, $query->first()->id);
    }
}
