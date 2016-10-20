<?php namespace Bedard\Shop\Tests\Models;

use Bedard\Shop\Models\Product;
use Bedard\Shop\Tests\Factory;

class ProductTest extends \PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

    public function test_product_name_is_required()
    {
        $this->setExpectedException(\October\Rain\Database\ModelException::class);
        Factory::fill(new Product, null, ['name'])->validate();
    }

    public function test_product_price_is_required()
    {
        $this->setExpectedException(\October\Rain\Database\ModelException::class);
        Factory::fill(new Product, null, ['price'])->validate();
    }

    public function test_product_price_must_be_a_positive_number()
    {
        $this->setExpectedException(\October\Rain\Database\ModelException::class);
        Factory::fill(new Product, ['price' => -1])->validate();
    }

    public function test_product_slug_is_required()
    {
        $this->setExpectedException(\October\Rain\Database\ModelException::class);
        Factory::fill(new Product, null, ['slug'])->validate();
    }

    public function test_product_slug_must_be_unique()
    {
        $this->setExpectedException(\October\Rain\Database\ModelException::class);
        $product = Factory::create(new Product);
        Factory::create(new Product, ['slug' => $product->slug]);
    }
}
