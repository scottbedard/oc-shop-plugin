<?php namespace Bedard\Shop\Models;

use Model;

/**
 * Inventory Model.
 */
class Inventory extends Model
{
    use \October\Rain\Database\Traits\Nullable,
        \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_shop_inventories';

    /**
     * @var array Attribute casting
     */
    protected $casts = [
        'quantity' => 'integer',
    ];

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'product_id',
        'quantity',
        'sku',
    ];

    /**
     * @var array Nullable fields
     */
    protected $nullable = ['sku'];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'sku' => 'unique:bedard_shop_inventories,sku',
        'quantity' => 'integer|min:0',
    ];

    public $customMessages = [
        'sku.unique' => 'bedard.shop::lang.inventories.form.sku_unique_error',
    ];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'product' => [
            'Bedard\Shop\Models\Product',
        ],
    ];

    public $belongsToMany = [
        'options' => [
            'Bedard\Shop\Models\Option',
            'table' => 'bedard_shop_inventory_option',
        ],
    ];
}
