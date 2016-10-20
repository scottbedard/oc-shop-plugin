<?php namespace Bedard\Shop\Tests;

use Bedard\Shop\Models\Product;

class ProductTest extends \PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

    public function test_true_is_true()
    {
        $this->assertTrue(true);
    }
}
