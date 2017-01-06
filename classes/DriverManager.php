<?php namespace Bedard\Shop\Classes;

use Exception;
use System\Classes\PluginManager;

class DriverManager
{
    use \October\Rain\Support\Traits\Singleton;

    /**
     * @var array   List of plugins.
     */
    private $plugins;

    /**
     * @var System\Classes\PluginManager
     */
    private $pluginManager;

    /**
     * @var array   List of payment drivers.
     */
    private $paymentDrivers;

    /**
     * @var array   List of shipping drivers.
     */
    private $shippingDrivers;

    /**
     * Initialize this singleton.
     */
    protected function init()
    {
        $this->pluginManager = PluginManager::instance();
    }

    /**
     * Return all plugins.
     *
     * @return array
     */
    protected function getPlugins()
    {
        if (! $this->plugins) {
            $this->plugins = $this->pluginManager->getPlugins();
        }

        return $this->plugins;
    }

    /**
     * Return a list of shipping drivers.
     *
     * @return array
     */
    public function getShippingDrivers()
    {
        if (! $this->shippingDrivers) {
            $this->registerShippingDrivers();
        }

        return $this->shippingDrivers;
    }

    /**
     * Register all shipping drivers.
     *
     * @return void
     */
    protected function registerShippingDrivers()
    {
        $plugins = $this->getPlugins();

        foreach ($plugins as $id => $plugin) {
            if (! method_exists($plugin, 'registerShippingDrivers')) {
                continue;
            }

            $pluginDrivers = $plugin->registerShippingDrivers();

            foreach ($plugin->registerShippingDrivers() as $driverClass) {
                $driver = new $driverClass;
                $details = $driver->driverDetails();
                $this->validateDriverDetails($details, $driverClass);

                $drivers[] = $driver;
            }
        }

        $this->shippingDrivers = $drivers;
    }

    /**
     * Validate driver details.
     *
     * @param  array        $details
     * @param  string       $driverClass
     * @throws \Exception
     * @return void
     */
    protected function validateDriverDetails($details, $driverClass)
    {
        if (! is_array($details)) {
            throw new Exception('An array must be returned from the driverDetails() method in '. $driverClass . '.');
        }

        if (! array_key_exists('name', $details) || ! is_string($details['name'])) {
            throw new Exception('A valid name must be returned from the driverDetails() method in ' . $driverClass . '.');
        }
    }
}
