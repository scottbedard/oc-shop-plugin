<?php namespace Bedard\Shop\Drivers;

use Bedard\Shop\Classes\PaymentDriver;
use Bedard\Shop\Models\Cart;

class NoPayment extends PaymentDriver
{
    /**
     * @return string   Form fields.
     */
    public $formFields = 'nopayment/fields.yaml';

    /**
     * Process a card.
     *
     * @param  Cart   $cart
     * @return void
     */
    public function process(Cart $cart)
    {
        // print_r ($this->getConfig());
    }
}
