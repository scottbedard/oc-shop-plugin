<?php namespace Bedard\Shop\Models;

use Model;

/**
 * Price Model.
 */
class Price extends Model
{
    use \Bedard\Shop\Traits\Timeable;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_shop_prices';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'discount_id',
        'end_at',
        'price',
        'product_id',
        'start_at',
    ];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'discount' => [
            'Bedard\Shop\Models\Discount',
        ],
        'product' => [
            'Bedard\Shop\Models\Product',
        ],
    ];
}
