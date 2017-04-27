<?php namespace Bedard\Shop\Classes;

use Bedard\Shop\Models\DriverConfig;
use Exception;
use October\Rain\Exception\ValidationException;
use October\Rain\Parse\Yaml;
use ReflectionClass;
use Validator;

abstract class Driver
{
    /**
     * @var array Custom validation messages.
     */
    public $customMessages = [];

    /**
     * @return string Form fields.
     */
    public $formFields;

    /**
     * @var array Validation rules.
     */
    public $rules = [];

    /**
     * After validate.
     *
     * @param  array    $data
     * @return void
     */
    public function afterValidate(array $data)
    {
    }

    /**
     * Before validate.
     *
     * @param  array    $data
     * @return void
     */
    public function beforeValidate(array $data)
    {
    }

    /**
     * Get the configuration model.
     *
     * @return Bedard\Shop\Models\DriverConfig
     */
    public function getConfigModel()
    {
        return DriverConfig::firstOrNew([
            'class' => get_class($this),
        ]);
    }

    /**
     * Get the configuration array.
     *
     * @return array
     */
    public function getConfig()
    {
        $model = $this->getConfigModel();

        return $model->getConfig();
    }

    /**
     * Get form fields.
     *
     * @return array
     */
    public function getFormFields()
    {
        if (! $this->formFields) {
            throw new Exception('Drivers must define a $formFields property, or overwrite the getFormFields() method.');
        }

        if (! is_string($this->formFields)) {
            throw new Exception('Driver $formFields property must be a string.');
        }

        $yaml = new Yaml;

        $reflector = new ReflectionClass(get_class($this));

        return $yaml->parseFile(dirname($reflector->getFileName()).'/'.$this->formFields);
    }

    /**
     * Save driver configuration.
     *
     * @param  array    $config
     * @return void
     */
    public function saveConfig(array $config)
    {
        $this->validate($config);

        $model = $this->getConfigModel(false);

        $model->config = json_encode($config);

        return $model->save();
    }

    /**
     * Validate form data.
     *
     * @param  array $formData
     * @return void
     * @throws \October\Rain\Exception\ValidationException
     */
    public function validate(array $formData)
    {
        $this->beforeValidate($formData);

        $validator = Validator::make($formData, $this->rules, $this->customMessages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $this->afterValidate($formData);
    }
}
