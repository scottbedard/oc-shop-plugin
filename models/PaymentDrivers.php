<?php namespace Bedard\Shop\Models;

use Model;

/**
 * Payment Driverz Model.
 */
class PaymentDrivers extends Model
{
    /**
     * @var array   Behaviors
     */
    public $implement = ['System.Behaviors.SettingsModel'];

    /**
     * @var string  Settings code
     */
    public $settingsCode = 'bedard_shop_payment_drivers';

    /**
     * @var string  Settings fields
     */
    public $settingsFields = 'fields.yaml';
}
