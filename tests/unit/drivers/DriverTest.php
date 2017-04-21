<?php namespace Bedard\Shop\Tests\Unit\Drivers;

use Bedard\Shop\Classes\Driver;
use Bedard\Shop\Classes\Factory;
use Bedard\Shop\Models\Cart;
use PluginTestCase;

class TestDriver extends Driver
{
}

class DriverTest extends PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

    public function test_getting_a_drivers_config_model()
    {
        $driver = new TestDriver;
        $model = $driver->getConfigModel();

        $this->assertEquals(get_class($driver), $model->class);
    }

    public function test_getting_the_config_as_an_array()
    {
        $driver = new TestDriver;
        $config = $driver->getConfig();

        $this->assertTrue(is_array($config));
    }

    public function test_saving_driver_configuration()
    {
        $driver = new TestDriver;
        $driver->saveConfig(['foo' => 'bar']);
        $config = $driver->getConfig();

        $this->assertEquals('bar', $config['foo']);
    }

    public function test_closing_a_cart()
    {
        $cart = Factory::create(new Cart);
        $driver = new TestDriver;

        $driver->close($cart);

        $this->assertNotNull(Cart::find($cart->id)->closed_at);
    }
}
