<?php namespace Bedard\Shop\Models;

use Model;

/**
 * Cart Settings Model.
 */
class ShippingSettings extends Model
{
    /**
     * @var array   Behaviors
     */
    public $implement = ['System.Behaviors.SettingsModel'];

    /**
     * @var string  Settings code
     */
    public $settingsCode = 'bedard_shop_settings_shipping';

    /**
     * @var string  Settings fields
     */
    public $settingsFields = 'fields.yaml';
}
