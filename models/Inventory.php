<?php namespace Bedard\Shop\Models;

use Model;

/**
 * Inventory Model.
 */
class Inventory extends Model
{
    use \October\Rain\Database\Traits\Nullable,
        \October\Rain\Database\Traits\Purgeable,
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
        'quantity',
        'sku',
        'value_ids',
    ];

    /**
     * @var array Nullable attributes.
     */
    protected $nullable = [
        'sku',
    ];

    /**
     * @var array Purgeable fields
     */
    public $purgeable = [
        'value_ids',
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
        'values' => [
            'Bedard\Shop\Models\OptionValue',
            'table' => 'bedard_shop_inventory_option_value',
            'key' => 'inventory_id',
            'otherKey' => 'option_value_id',
        ],
    ];

    /**
     * @var array Validation
     */
    public $rules = [
        'sku' => 'unique:bedard_shop_inventories,sku',
        'quantity' => 'integer|min:0',
    ];

    public $customMessages = [
        'sku.unique' => 'bedard.shop::lang.inventories.form.sku_unique',
    ];

    /**
     * After save.
     *
     * @return void
     */
    public function afterSave()
    {
        $this->saveValues();
    }

    /**
     * Before validate.
     *
     * @return void
     */
    public function beforeValidate()
    {
        $this->defineSkuValidationRule();
    }

    /**
     * Define the SKU validation rule.
     *
     * @return void
     */
    protected function defineSkuValidationRule()
    {
        if ($this->id) {
            $this->rules['sku'] = 'unique:bedard_shop_inventories,sku,'.$this->id;
        }
    }

    /**
     * Save related values.
     *
     * @return void
     */
    protected function saveValues()
    {
        $values = $this->getOriginalPurgeValue('value_ids') ?: [];

        $this->values()->sync($values);
    }
}
