<?php namespace Bedard\Shop\Models;

use Model;

/**
 * Option Model
 */
class Option extends Model
{

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_shop_options';

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
    public $belongsToMany = [
        'inventories' => [
            'Bedard\Shop\Models\Inventory',
            'table' => 'bedard_shop_inventory_option',
        ],
    ];

    public $hasMany = [
        'values' => [
            'Bedard\Shop\Models\OptionValue',
            'delete' => true,
        ]
    ];

}
