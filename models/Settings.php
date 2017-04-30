<?php namespace Bedard\Shop\Models;

use Model;

/**
 * Api Settings Model.
 */
class Settings extends Model
{
    /**
     * @var array   Behaviors
     */
    public $implement = ['System.Behaviors.SettingsModel'];

    /**
     * @var string  Settings code
     */
    public $settingsCode = 'bedard_shop_api_settings_general';

    /**
     * @var string  Settings fields
     */
    public $settingsFields = 'fields.yaml';

    public static function getLifespan()
    {
        $lifespan = self::get('cart_lifespan') ?: 360;

        return (int) $lifespan;
    }
}
