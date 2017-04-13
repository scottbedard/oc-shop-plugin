<?php namespace Bedard\Shop\FormWidgets;

use Backend\Classes\FormWidgetBase;
use Bedard\Shop\Classes\DriverManager;
use Bedard\Shop\Models\DriverConfig;

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
    public function getSaveValue($value)
    {
        return $value;
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
     * Load a driver's configuration form.
     */
    public function onDriverClicked()
    {
        $class = input('class');
        $driver = new $class;

        $form = $this->makeConfigFromArray($driver->getFormFields());
        $form->model = DriverConfig::findByClass($class);

        return $this->makePartial('popup', [
            'details' => $driver->driverDetails(),
            'form' => $this->makeWidget('Backend\Widgets\Form', $form),
        ]);
    }

    /**
     * Save a driver's configuration.
     */
    public function onDriverSaved()
    {
        // @todo: save driver config

        return [];
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
    public function render()
    {
        $this->prepareVars();

        return $this->makePartial('driverconfigs');
    }
}
