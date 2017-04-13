<?php namespace Bedard\Shop\Drivers;

use Bedard\Shop\Classes\Driver;

class NoPayment extends Driver
{
    /**
     * Driver details.
     *
     * @return array
     */
    public function driverDetails()
    {
        return [
            'name' => 'No Payment',
        ];
    }
}
