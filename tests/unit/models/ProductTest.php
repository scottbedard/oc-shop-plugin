<?php namespace Bedard\Shop\Tests\Unit\Models;

use PluginTestCase;
use Bedard\Shop\Factory;
use Bedard\Shop\Models\Product;
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
}
