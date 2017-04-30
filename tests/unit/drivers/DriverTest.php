<?php namespace Bedard\Shop\Tests\Unit\Drivers;

use Bedard\Shop\Classes\Driver;
use Bedard\Shop\Tests\Unit\ShopTestCase;
use Exception;
use October\Rain\Exception\ValidationException;

//
// fixtures
//
class TestDriver extends Driver
{
}

class DriverValidationException extends Exception
{
}

class AfterValidateDriver extends Driver {
    public function afterValidate(array $data)
    {
        throw new DriverValidationException;
    }
}

class BeforeValidateDriver extends Driver {
    public function beforeValidate(array $data)
    {
        throw new DriverValidationException;
    }
}

//
// tests
//
class DriverTest extends ShopTestCase
{
    public function test_getting_a_drivers_config_model()
    {
        $driver = new TestDriver;
        $model = $driver->getConfigModel();

        $this->assertEquals(get_class($driver), $model->class);
    }

    public function test_getting_the_config_as_an_array()
    {
        $driver = new TestDriver;
        $config = $driver->getConfig();

        $this->assertTrue(is_array($config));
    }

    public function test_saving_driver_configuration()
    {
        $driver = new TestDriver;
        $driver->saveConfig(['foo' => 'bar']);
        $config = $driver->getConfig();

        $this->assertEquals('bar', $config['foo']);
    }

    public function test_driver_validation()
    {
        $driver = new TestDriver;
        $driver->rules = ['foo' => 'required'];

        $this->setExpectedException(ValidationException::class);

        $driver->saveConfig([]);
    }

    public function test_before_validate()
    {
        $driver = new BeforeValidateDriver;

        $this->setExpectedException(DriverValidationException::class);

        $driver->saveConfig([]);
    }

    public function test_after_validate()
    {
        $driver = new AfterValidateDriver;

        $this->setExpectedException(DriverValidationException::class);

        $driver->saveConfig(['foo' => 'bar']);
    }
}
