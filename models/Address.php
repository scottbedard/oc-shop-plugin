<?php namespace Bedard\Shop\Models;

use Model;

/**
 * Address Model.
 */
class Address extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_shop_addresses';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'customer' => [
            'Bedard\Shop\Models\Customer',
        ],
    ];
}
