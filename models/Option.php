<?php namespace Bedard\Shop\Models;

use Model;

/**
 * Option Model.
 */
class Option extends Model
{
    use \October\Rain\Database\Traits\Validation;

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
    protected $fillable = [
        'id',
        'name',
        'placeholder',
        'product_id',
        'sort_order',
    ];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'name' => 'required',
    ];

    public $customMessages = [
        'name.required' => 'bedard.shop::lang.options.form.name_required_error',
    ];

    /**
     * @var array Relations
     */
    public $belongsToMany = [
        'inventories' => [
            'Bedard\Shop\Models\Inventory',
            'table' => 'bedard_shop_inventory_option',
            'delete' => true,
        ],
    ];

    public $hasMany = [
        'values' => [
            'Bedard\Shop\Models\OptionValue',
            'delete' => true,
            'order' => 'sort_order',
        ],
    ];
}
