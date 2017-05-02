<?php namespace Bedard\Shop\Models;

use Model;

/**
 * Address Model.
 */
class Address extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_shop_addresses';

    /**
     * @var array Default attributes
     */
    public $attributes = [
        'country_code' => null,
        'locality' => null,
        'postal_code' => null,
        'region' => null,
        'street' => '[]',
    ];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'country_code',
        'locality',
        'postal_code',
        'region',
        'street',
    ];

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Jsonable fields
     */
    protected $jsonable = [
        'street',
    ];

    /**
     * @var array Relations
     */
    public $belongsToMany = [
        'users' => [
            'RainLab\User\Models\User',
            'table' => 'bedard_shop_address_user',
        ],
    ];

    public $hasMany = [
        'carts' => [
            'Bedard\Shop\Models\Cart',
        ],
    ];

    /**
     * @var array Validation
     */
    public $rules = [
        'street' => 'array',
    ];
}
