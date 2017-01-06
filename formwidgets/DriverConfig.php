<?php namespace Bedard\Shop\FormWidgets;

use Backend\Classes\FormWidgetBase;
use Bedard\Shop\Classes\DriverManager;

/**
 * DriverConfig Form Widget.
 */
class DriverConfig extends FormWidgetBase
{
    /**
     * {@inheritdoc}
     */
    protected $defaultAlias = 'bedard_shop_driver_config';

    /**
     * @var \Bedard\Shop\Classes\DriverManager;
     */
    protected $manager;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        $this->manager = DriverManager::instance();
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $this->prepareVars();

        return $this->makePartial('driverconfig');
    }

    /**
     * Prepares the form widget view data.
     */
    public function prepareVars()
    {
        $this->vars['drivers'] = $this->getShippingDriverDetails();
    }

    /**
     * {@inheritdoc}
     */
    public function loadAssets()
    {
        $this->addJs('/plugins/bedard/shop/assets/dist/vendor.js');
        $this->addJs('/plugins/bedard/shop/assets/dist/driverconfig.js', 'Bedard.Shop');
    }

    /**
     * {@inheritdoc}
     */
    public function getSaveValue($value)
    {
        return $value;
    }

    /**
     * Get the driver details.
     *
     * @return array
     */
    protected function getShippingDriverDetails()
    {
        foreach ($this->manager->getShippingDrivers() as $driver) {
            $details[get_class($driver)] = $driver->driverDetails();
        }

        return $details;
    }
}
