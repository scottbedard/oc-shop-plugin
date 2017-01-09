<?php namespace Bedard\Shop\FormWidgets;

use Bedard\Shop\Models\DriverConfig as ConfigModel;
use Form;
use Model;
use Backend\Classes\FormWidgetBase;
use Bedard\Shop\Classes\DriverManager;
use Bedard\Shop\Interfaces\DriverInterface;
use October\Rain\Exception\ValidationException;

/**
 * DriverConfig Form Widget.
 */
class DriverConfig extends FormWidgetBase
{
    use \Bedard\Shop\Traits\LangJsonable;

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
        $this->vars['drivers'] = $this->getShippingDrivers();
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
     * Get the form for a driver.
     *
     * @param  DriverInterface $driver
     * @return object
     */
    protected function getDriverForm(DriverInterface $driver)
    {
        $model = new Model;
        // foreach (array_merge(array_keys($fields), array_keys($tabFields)) as $key) {
        //     $model->$key = $driver->getConfig($key);
        // }

        $form = $this->makeConfigFromArray($driver->getFormFields());
        $form->model = $model;

        return $form;
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
    protected function getShippingDrivers()
    {
        foreach ($this->manager->getShippingDrivers() as $driver) {
            $drivers[] = [
                'driver' => get_class($driver),
                'details' => $driver->driverDetails(),
            ];
        }

        return $drivers;
    }

    /**
     * Load the driver settings.
     *
     * @return string
     */
    public function onLoadDriverSettings()
    {
        $driverClass = input('driver');
        $driver = new $driverClass;

        $config = $this->getDriverForm($driver);
        $form = $this->makeWidget('Backend\Widgets\Form', $config);

        return $this->makePartial('popup', [
            'driver' => $driverClass,
            'details' => $driver->driverDetails(),
            'form' => $form->render(),
        ]);
    }

    /**
     * Validate the form.
     *
     * @return void
     * @throws \AjaxException
     */
    public function onFormSubmitted()
    {
        // grab our relevant input data
        $data = input();
        $driverClass = $data['_driver'];
        $driver = new $driverClass;

        // clean up fields not needed for driver data
        unset($data['_driver']);
        unset($data['_session_key']);
        unset($data['_token']);

        // give the driver a change to validate this form if they want to
        if (method_exists($driver, 'validate')) $driver->validate($data);

        // if the driver defined it's own save method, call it
        if (method_exists($driver, 'save')) $driver->save($data);

        // otherwise just use the default save
        else $this->save($driverClass, $data);
    }

    /**
     * Save a driver.
     *
     * @param  name     $driver     The class name of the driver.
     * @param  array    $config     Driver data to save.
     * @return void
     */
    protected function save($driverClass, $config)
    {
        $model = ConfigModel::firstOrNew(['driver' => $driverClass]);
        $model->config = $config;
        $model->save();
    }
}
