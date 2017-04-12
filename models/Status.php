<?php namespace Bedard\Shop\Models;

use Model;

/**
 * Status Model.
 */
class Status extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_shop_statuses';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'color',
        'icon',
        'name',
    ];

    /**
     * @var array Relations
     */
    public $belongsToMany = [
        'carts' => [
            'Bedard\Shop\Models\Cart',
            'pivot' => ['created_at'],
            'table' => 'bedard_shop_cart_status',
        ],
    ];
}
