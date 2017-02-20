<?php namespace Bedard\Shop\Tests\Unit\Models;

use Bedard\Shop\Factory;
use Bedard\Shop\Models\Category;
use Bedard\Shop\Models\Product;
use PluginTestCase;

class CategoryTest extends PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

    public function test_categoriess_can_belong_to_products()
    {
        $category = Factory::create(new Category);
        $product = Factory::create(new Product);
        $category->products()->attach($product);
        $this->assertEquals($product->id, $category->products()->find($product->id)->id);
    }
}
