<?php namespace Bedard\Shop\Tests;

class FooTest extends \PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

    public function test_true_is_true()
    {
        $this->assertTrue(true);
    }
}
