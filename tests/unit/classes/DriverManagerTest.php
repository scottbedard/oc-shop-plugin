<?php namespace Bedard\Shop\Tests\Unit\Classes;

use Bedard\Shop\Classes\DriverManager;
use PluginTestCase;

class DriverManagerTest extends PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

    public function test_getting_an_array_of_drivers()
    {
        $manager = new DriverManager;
        $drivers = $manager->getDriversByType('payment');

        $this->assertEquals(1, count($drivers));
        $this->assertEquals('payment', $drivers[0]['type']);
    }

    public function test_finding_a_driver_by_class()
    {
        $manager = new DriverManager;
        $nopayment = $manager->getRegistration('Bedard\Shop\Drivers\NoPayment');

        $this->assertEquals('Bedard\Shop\Drivers\NoPayment', $nopayment['class']);
    }
}
