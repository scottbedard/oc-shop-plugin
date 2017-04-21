<?php namespace Bedard\Shop\FormWidgets;

use Backend\Classes\FormWidgetBase;
use Bedard\Shop\Classes\DriverManager;
use Exception;

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
     * Filter out form data from popup input.
     *
     * @param  array $data
     * @return array
     */
    public function getFormData($data)
    {
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

        $manager = new DriverManager;
        $registration = $manager->getRegistration($class);

        $model = $driver->getConfigModel();
        $model->populate();

        $form = $this->makeConfigFromArray($driver->getFormFields());
        $form->model = $model;

        try {
            $form->model->populate();
        } catch (Exception $e) {
            // json decoding crashes if the content was incorrectly saved as []
        }

        return $this->makePartial('popup', [
            'class' => $class,
            'title' => $registration['name'],
            'form' => $this->makeWidget('Backend\Widgets\Form', $form),
        ]);
    }

    /**
     * Save a driver's configuration.
     */
    public function onDriverSaved()
    {
        $data = input();
        $formData = $this->getFormData(input());

        $driver = new $data['_class'];
        $driver->saveConfig($formData);
    }

    /**
     * Prepares the form widget view data.
     */
    public function prepareVars()
    {
        $manager = new DriverManager;
        $drivers = $this->getConfig('drivers');

        $this->vars['drivers'] = $manager->getDriversByType($drivers);
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
