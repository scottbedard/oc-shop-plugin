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
     * Filter out form data from popup input.
     *
     * @param  array $data
     * @return array
     */
    public function getFormData($data) {
        $formData = [];

        foreach ($data as $key => $value) {
            if ($key[0] !== '_') {
                $formData[$key] = $value;
            }
        }

        return $formData;
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
        $form->model = DriverConfig::whereDriver($class)->firstOrFail();
        $form->model->populate();

        return $this->makePartial('popup', [
            'class' => get_class($driver),
            'details' => $driver->driverDetails(),
            'form' => $this->makeWidget('Backend\Widgets\Form', $form),
        ]);
    }

    /**
     * Save a driver's configuration.
     */
    public function onDriverSaved()
    {
        $data = input();
        $formData = $this->getFormData($data);

        $driver = new $data['_class'];
        $driver->validate($formData);

        $config = DriverConfig::whereDriver($data['_class'])->firstOrFail();
        $config->config = $formData;
        $config->save();
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
