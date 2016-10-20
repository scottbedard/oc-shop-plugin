<?php namespace Bedard\Shop\Tests;

use Bedard\Shop\Models\Product;

class ProductTest extends \PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

    public function test_create()
    {
        $product = Product::create();
        $this->assertEquals(1, Product::all()->count());
    }
}
