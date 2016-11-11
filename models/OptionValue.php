<?php namespace Bedard\Shop\Models;

use Model;

/**
 * OptionValue Model.
 */
class OptionValue extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_shop_option_values';

    /**
     * @var array Attribute casting
     */
    protected $casts = [
        'id' => 'integer',
    ];

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'id',
        'name',
        'option_id',
        'sort_order',
    ];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'option' => [
            'Bedard\Shop\Models\Option',
        ],
    ];

    public $belongsToMany = [
        'inventories' => [
            'Bedard\Shop\Models\Inventory',
            'table' => 'bedard_shop_inventory_option_values',
            'delete' => true,
        ],
    ];
}
