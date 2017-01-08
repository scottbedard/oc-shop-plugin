<?php namespace Bedard\Shop\FormWidgets;

use Model;
use Backend\Classes\FormWidgetBase;
use Bedard\Shop\Classes\DriverManager;
use Bedard\Shop\Interfaces\DriverInterface;

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
    protected function getShippingDriverDetails()
    {
        foreach ($this->manager->getShippingDrivers() as $driver) {
            $details[] = [
                'class' => get_class($driver),
                'details' => $driver->driverDetails(),
            ];
        }

        return $details;
    }

    /**
     * Load the driver settings.
     *
     * @return string
     */
    public function onLoadDriverSettings()
    {
        $input = input('driver');
        $driver = new $input['class'];

        $form = $this->getDriverForm($driver);

        return $this->makePartial('popup', [
            'driver' => $driver,
            'form' => $this->makeWidget('Backend\Widgets\Form', $form)->render(),
        ]);
    }
}
