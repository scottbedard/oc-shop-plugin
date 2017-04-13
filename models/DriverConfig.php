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
    protected $encryptable = ['config'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'driver',
        'config',
    ];

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Jsonable fields
     */
    protected $jsonable = ['config'];

    /**
     * Find a driver model and set it's values so we can render a form.
     *
     * @param  string   $class
     * @return void
     */
    public static function findByClass($class)
    {
        $model = self::firstOrNew(['driver' => $class]);

        if (is_array($model->config)) {
            foreach ($model->config as $key => $value) {
                $model->$key = $value;
            }
        }

        return $model;
    }
}
