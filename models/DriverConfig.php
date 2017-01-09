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
    protected $jsonable = [
        'config',
    ];
}
