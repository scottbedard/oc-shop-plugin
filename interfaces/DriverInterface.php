<?php namespace Bedard\Shop\Interfaces;

interface DriverInterface
{
    /**
     * Return details about this driver.
     *
     * @return array
     */
    public function driverDetails();
}
