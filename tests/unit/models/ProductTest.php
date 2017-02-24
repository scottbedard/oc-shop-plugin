<?php namespace Bedard\Shop\Tests\Unit\Models;

use PluginTestCase;
use Bedard\Shop\Models\Option;
use Bedard\Shop\Models\Product;
use Bedard\Shop\Classes\Factory;
use Bedard\Shop\Models\Category;

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
}
