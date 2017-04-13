<?php namespace Bedard\Shop\Classes;

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
     * Validate form data.
     *
     * @param  array $formData
     * @return void
     * @throws \October\Rain\Exception\ValidationException
     */
    public function validate(array $formData)
    {
        $validator = Validator::make($formData, $this->rules, $this->customMessages);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
    }
}
