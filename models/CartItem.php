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
     * @var array Default attributes
     */
    public $attributes = [
        'cart_id' => null,
        'created_at' => null,
        'deleted_at' => null,
        'id' => null,
        'inventory_id' => null,
        'is_reduced' => false,
        'product_id' => null,
        'quantity' => 0,
        'updated_at' => null,
    ];

    /**
     * @var array Attribute casting
     */
    protected $casts = [
        'cart_id' => 'integer',
        'id' => 'integer',
        'inventory_id' => 'integer',
        'is_reduced' => 'boolean',
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
        'is_reduced',
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

    /**
     * Reduce the available inventory.
     *
     * @return void
     */
    public function reduceAvailableInventory()
    {
        if (! $this->is_reduced) {
            $this->load('inventory');
            $this->inventory->quantity -= $this->quantity;
            $this->inventory->save();

            $this->is_reduced = true;
            $this->save();
        }
    }
}
