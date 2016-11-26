<?php namespace Bedard\Shop\Models;

use Model;

/**
 * CartItem Model
 */
class CartItem extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_shop_cart_items';

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
        'cart' => [
            'Bedard\Shop\Models\Cart',
        ],
        'inventory' => [
            'Bedard\Shop\Models\Inventory',
        ],
    ];

}
