<?php namespace Bedard\Shop\Tests;

use Bedard\Shop\Models\Product;
use Bedard\Shop\Tests\Factory;

class ProductTest extends \PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

    public function test_name_is_required()
    {
        $product = Factory::fill(new Product, ['name' => null]);
        $this->setExpectedException(\October\Rain\Database\ModelException::class);
        $product->validate();
    }

    public function test_slug_is_required()
    {
        $product = Factory::fill(new Product, ['slug' => null]);
        $this->setExpectedException(\October\Rain\Database\ModelException::class);
        $product->validate();
    }

    public function test_slug_must_be_unique()
    {
        $product = Factory::create(new Product);
        $this->setExpectedException(\October\Rain\Database\ModelException::class);
        $duplicate = Factory::create(new Product, ['slug' => $product->slug]);
    }
}
