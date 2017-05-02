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
        'country' => null,
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
    public $belongsToMany = [];

    /**
     * @var array Validation
     */
    public $rules = [
        'street' => 'array',
    ];
}
