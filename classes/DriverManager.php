<?php namespace Bedard\Shop\Classes;

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
            if (! method_exists($plugin, 'registerShopDrivers')) {
                continue;
            }

            foreach ($plugin->registerShopDrivers() as $driver) {
                if ($driver['type'] === $type) {
                    $drivers[] = $driver;
                }
            }
        }

        // attach driver details to the array
        return array_map(function ($driver) {
            $details = (new $driver['class'])->driverDetails();
            $driver['details'] = $details;

            if (
                array_key_exists('name', $driver['details']) &&
                is_string($driver['details']['name'])
            ) {
                $driver['details']['name'] = trans($driver['details']['name']);
            }

            return $driver;
        }, $drivers);
    }
}
