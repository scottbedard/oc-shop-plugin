<?php namespace Bedard\Shop\FormWidgets;

use Backend\Classes\FormWidgetBase;
use Bedard\Shop\Classes\DriverManager;

/**
 * DriverConfig Form Widget
 */
class DriverConfig extends FormWidgetBase
{
    /**
     * {@inheritDoc}
     */
    protected $defaultAlias = 'bedard_shop_driver_config';

    /**
     * {@inheritDoc}
     */
    public function init()
    {
    }

    /**
     * {@inheritDoc}
     */
    public function render()
    {
        $this->prepareVars();
        return $this->makePartial('driverconfig');
    }

    /**
     * Prepares the form widget view data
     */
    public function prepareVars()
    {
        $this->vars['calculators'] = $this->getShippingCalculators();
        $this->vars['name'] = $this->formField->getName();
        $this->vars['value'] = $this->getLoadValue();
        $this->vars['model'] = $this->model;
    }

    /**
     * {@inheritDoc}
     */
    public function loadAssets()
    {
        $this->addCss('css/driverconfig.css', 'Bedard.Shop');
        $this->addJs('js/driverconfig.js', 'Bedard.Shop');
    }

    /**
     * {@inheritDoc}
     */
    public function getSaveValue($value)
    {
        return $value;
    }

    protected function getShippingCalculators()
    {
        $manager = DriverManager::instance();

        return $manager->getShippingDrivers();
    }
}
