<?php namespace Bedard\Shop\Models;

use Model;

/**
 * Customer Model.
 */
class Customer extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_shop_customers';

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
    public $hasMany = [
        'addresses' => [
            'Bedard\Shop\Models\Address',
        ],
    ];

    public $belongsTo = [
        'user' => [
            'RainLab\User\Models\User',
        ],
    ];
}
