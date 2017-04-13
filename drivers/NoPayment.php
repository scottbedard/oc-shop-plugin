<?php namespace Bedard\Shop\Drivers;

use Bedard\Shop\Classes\Driver;

class NoPayment extends Driver
{
    /**
     * @return string   Form fields.
     */
    public $formFields = 'nopayment/fields.yaml';

    /**
     * Driver details.
     *
     * @return array
     */
    public function driverDetails()
    {
        return [
            'name' => 'bedard.shop::lang.drivers.nopayment.name',
            'thumbnail' => null,
        ];
    }
}
