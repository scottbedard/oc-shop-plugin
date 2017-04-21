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
     * Get all drivers.
     *
     * @return array
     */
    public function getDrivers()
    {
        $drivers = [];

        foreach ($this->manager->getPlugins() as $id => $plugin) {
            if (! method_exists($plugin, 'registerShopDrivers')) {
                continue;
            }

            foreach ($plugin->registerShopDrivers() as $driver) {
                if (array_key_exists('name', $driver)) {
                    $driver['name'] = trans($driver['name']);
                }

                $drivers[] = $driver;
            }
        }

        return $drivers;
    }

    /**
     * Get a type of driver.
     *
     * @param  string   $type
     * @return array
     */
    public function getDriversByType($type)
    {
        return array_filter($this->getDrivers(), function ($driver) use ($type) {
            return $driver['type'] === $type;
        });
    }

    /**
     * Get the registration of a particular driver.
     *
     * @param  string       $class
     * @return array|null
     */
    public function getRegistration($class)
    {
        foreach ($this->getDrivers() as $driver) {
            if ($driver['class'] === $class) {
                return $driver;
            }
        }

        return null;
    }
}
