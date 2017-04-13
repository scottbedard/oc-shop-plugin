<?php namespace Bedard\Shop\Classes;

use Lang;
use System\Classes\PluginManager;

class DriverManager
{
    /**
     * @var \System\Classes\PluginManager Manager
     */
    protected $manager;

    /**
     * Construct.
     *
     * @return void
     */
    public function __construct()
    {
        $this->manager = PluginManager::instance();
    }

    /**
     * Get a type of driver.
     *
     * @param  string   $type
     * @return array
     */
    public function getDrivers($type)
    {
        // find all drivers of a particular type
        $drivers = [];

        foreach ($this->manager->getPlugins() as $id => $plugin) {
            if (! method_exists($plugin, 'registerShopDrivers') ||
                ! array_key_exists($type, $plugin->registerShopDrivers())) {
                continue;
            }

            foreach ($plugin->registerShopDrivers()[$type] as $driver) {
                $drivers[] = new $driver;
            }
        }

        // extract the driver details
        $drivers = array_map(function ($driver) {
            $details = $driver->driverDetails();
            $details['class'] = get_class($driver);
            $details['name'] = Lang::get($details['name']);

            return $details;
        }, $drivers);

        return $drivers;
    }
}
