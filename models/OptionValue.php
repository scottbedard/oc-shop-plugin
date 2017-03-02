<?php namespace Bedard\Shop\Models;

use Model;

/**
 * OptionValue Model.
 */
class OptionValue extends Model
{
    use \October\Rain\Database\Traits\Purgeable,
        \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_shop_option_values';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        '_key',
        'name',
        'sort_order',
    ];

    /**
     * @var array Purgeable fields
     */
    public $purgeable = [
        '_key',
    ];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'option' => [
            'Bedard\Shop\Models\Option',
        ],
    ];

    /**
     * @var array Relations
     */
    public $belongsToMany = [
        'inventories' => [
            'Bedard\Shop\Models\Inventory',
            'delete' => true,
            'key' => 'option_value_id',
            'otherKey' => 'inventory_id',
            'table' => 'bedard_shop_inventory_option_value',
        ],
    ];

    /**
     * @var array Validation
     */
    public $rules = [
        'name' => 'required|min:1',
    ];

    public function beforeDelete()
    {
        $this->inventories->each(function ($inventory) {
            $inventory->delete();
        });
    }
}
