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
    public $settingsCode = 'bedard_shop_api_settings';

    /**
     * @var string  Settings fields
     */
    public $settingsFields = 'fields.yaml';

    /**
     * Get the enabled status of the api endpoints.
     *
     * @return bool
     */
    public static function isEnabled()
    {
        return self::get('is_enabled', false);
    }

    /**
     * Get options for categories endpoint.
     *
     * @return array
     */
    public static function getCategoriesOptions()
    {
        return self::get('categories') ?: [];
    }

    /**
     * Get options for category endpoint.
     *
     * @return array
     */
    public static function getCategoryOptions()
    {
        return self::get('category') ?: [];
    }

    /**
     * Get options for products endpoint.
     *
     * @return array
     */
    public static function getProductsOptions()
    {
        return self::get('products') ?: [];
    }

    /**
     * Get options for product endpoint.
     *
     * @return array
     */
    public static function getProductOptions()
    {
        return self::get('product') ?: [];
    }
}
