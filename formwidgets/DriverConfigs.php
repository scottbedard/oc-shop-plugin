<?php namespace Bedard\Shop\FormWidgets;

use Backend\Classes\FormWidgetBase;
use Bedard\Shop\Classes\DriverManager;

/**
 * DriverConfigs Form Widget.
 */
class DriverConfigs extends FormWidgetBase
{
    /**
     * {@inheritdoc}
     */
    protected $defaultAlias = 'bedard_shop_driver_configs';

    /**
     * @var \Bedard\Shop\Classes\DriverManager
     */
    protected $manager;

    /**
     * Construct.
     */
    public function __construct()
    {
        call_user_func_array('parent::__construct', func_get_args());

        $this->manager = new DriverManager;
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $this->prepareVars();

        return $this->makePartial('driverconfigs');
    }

    /**
     * Prepares the form widget view data.
     */
    public function prepareVars()
    {
        $drivers = $this->getConfig('drivers');

        $this->vars['drivers'] = $this->manager->getDrivers($drivers);
    }

    /**
     * {@inheritdoc}
     */
    public function loadAssets()
    {
        $this->addJs('/plugins/bedard/shop/assets/dist/vendor.min.js', 'Bedard.Shop');
        $this->addJs('/plugins/bedard/shop/assets/dist/driver_configs.min.js', 'Bedard.Shop');
    }

    /**
     * {@inheritdoc}
     */
    public function getSaveValue($value)
    {
        return $value;
    }
}
