<?php namespace Bedard\Shop\Models;

use Crypt;
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
        'is_enabled',
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
     * Before save.
     *
     * @return void
     */
    public function beforeSave()
    {
        $this->preventEmptyConfig();
    }

    /**
     * Get the model's config array.
     *
     * @return array
     */
    public function getConfig()
    {
        if (is_array($this->config)) {
            return $this->config;
        }

        return json_decode($this->config ?: '[]', true);
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

        if (is_array($config)) {
            foreach ($config as $key => $value) {
                $this->$key = $value;
            }
        }
    }

    /**
     * Prevent empty configs.
     *
     * @return void
     */
    public function preventEmptyConfig()
    {
        // if (! $this->getConfig()) {
        //     print_r ('ye');
        //     $this->config = Crypt::encrypt('[]');
        // }
    }

    /**
     * Select enabled drivers.
     *
     * @param  \October\Rain\Database\Builder $query
     * @return \October\Rain\Database\Builder
     */
    public function scopeIsEnabled($query)
    {
        return $query->whereIsEnabled(true);
    }
}
