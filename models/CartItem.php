<?php namespace Bedard\Shop\Models;

use Model;

/**
 * CartItem Model.
 */
class CartItem extends Model
{
    use \October\Rain\Database\Traits\SoftDelete;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_shop_cart_items';

    /**
     * @var array Attribute casting
     */
    protected $casts = [
        'cart_id' => 'integer',
        'id' => 'integer',
        'inventory_id' => 'integer',
        'product_id' => 'integer',
        'quantity' => 'integer',
    ];

    /**
     * @var array Date fields
     */
    protected $dates = ['deleted_at'];

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'cart_id',
        'inventory_id',
        'product_id',
        'quantity',
    ];

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
        'product' => [
            'Bedard\Shop\Models\Product',
        ],
    ];
}
