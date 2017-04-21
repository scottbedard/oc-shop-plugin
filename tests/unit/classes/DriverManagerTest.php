<?php namespace Bedard\Shop\Tests\Unit\Classes;

use Bedard\Shop\Classes\DriverManager;
use PluginTestCase;

class DriverManagerTest extends PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

    public function test_getting_an_array_of_drivers()
    {
        $manager = new DriverManager;
        $drivers = $manager->getDrivers('payment');

        $this->assertEquals(1, count($drivers));
        $this->assertEquals('payment', $drivers[0]['type']);
    }
}
