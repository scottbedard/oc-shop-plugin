<?php namespace Bedard\Shop\Classes;

use Exception;
use October\Rain\Parse\Yaml;
use ReflectionClass;

abstract class Driver
{
    /**
     * @return string   Form fields.
     */
    public $formFields;

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
}
