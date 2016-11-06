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

    public static function categoriesHideEmpty()
    {
        return self::get('categories_hide_empty', false);
    }

    public static function categoriesLoadThumbnails()
    {
        return self::get('categories_load_thumbnails', false);
    }

    public static function categoriesSelect()
    {
        return self::get('categories_select', []);
    }

    /**
     * Determines if the HTTP API is enabled.
     *
     * @return bool
     */
    public static function isEnabled()
    {
        return self::get('is_enabled', false);
    }
}
