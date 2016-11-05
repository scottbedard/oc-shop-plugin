<?php namespace Bedard\Shop\Models;

use Model;

/**
 * Api Settings Model.
 */
class ApiSettings extends Model
{

    /**
     * @var array   Behaviors
     */
    public $implement = ['System.Behaviors.SettingsModel'];

    /**
     * @var string  Settings code
     */
    public $settingsCode = 'bedard_shop_settings_api';

    /**
     * @var string  Settings fields
     */
    public $settingsFields = 'fields.yaml';

    /**
     * Determines if the HTTP API is enabled.
     *
     * @return boolean
     */
    public static function isEnabled()
    {
        return self::get('is_enabled', false);
    }
}
