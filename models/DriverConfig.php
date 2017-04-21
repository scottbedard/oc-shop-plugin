<?php namespace Bedard\Shop\Models;

use Model;

/**
 * DriverConfig Model.
 */
class DriverConfig extends Model
{
    use \October\Rain\Database\Traits\Encryptable;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_shop_driver_configs';

    /**
     * @var array Default attributes
     */
    public $attributes = [
        'config' => '',
    ];

    /**
     * @var array Encrypted fields
     */
    protected $encryptable = [
        'config',
    ];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'class',
        'config',
    ];

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Jsonable fields
     */
    protected $jsonable = [
        'config',
    ];

    /**
     * Get the model's config array.
     *
     * @return array
     */
    public function getConfig()
    {
        $config = $this->config ?: [];

        return is_string($config) ? json_decode($config, true) : $config;
    }

    /**
     * Populate attriutes based on config so a form can be rendered.
     *
     * @param  string   $class
     * @return void
     */
    public function populate()
    {
        $config = $this->getConfig();

        foreach ($config as $key => $value) {
            $this->$key = $value;
        }
    }
}
