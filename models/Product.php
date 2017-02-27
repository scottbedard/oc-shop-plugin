<?php namespace Bedard\Shop\Models;

use Model;

/**
 * Product Model.
 */
class Product extends Model
{
    use \Bedard\Shop\Traits\Subqueryable,
        \October\Rain\Database\Traits\Purgeable,
        \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_shop_products';

    /**
     * @var array Default attributes
     */
    public $attributes = [
        'base_price' => 0,
        'description_html' => '',
        'description_plain' => '',
        'is_enabled' => true,
    ];

    /**
     * @var array Attribute casting
     */
    protected $casts = [
        'id' => 'integer',
        'price' => 'float',
        'base_price' => 'float',
        'is_enabled' => 'boolean',
    ];

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'base_price',
        'description_html',
        'description_plain',
        'is_enabled',
        'name',
        'options_inventories',
        'slug',
    ];

    /**
     * @var array Purgeable fields
     */
    public $purgeable = [
        'options_inventories',
    ];

    /**
     * @var array Relations
     */
    public $belongsToMany = [
        'categories' => [
            'Bedard\Shop\Models\Category',
            'table' => 'bedard_shop_category_product',
        ],
    ];

    public $hasMany = [
        'inventories' => [
            'Bedard\Shop\Models\Inventory',
            'delete' => true,
        ],
        'options' => [
            'Bedard\Shop\Models\Option',
            'delete' => true,
            'order' => 'sort_order',
        ],
    ];

    /**
     * @var array Validation
     */
    public $rules = [
        'name' => 'required',
        'base_price' => 'required|numeric|min:0',
        'slug' => 'required|unique:bedard_shop_products',
    ];

    /**
     * After save.
     *
     * @return void
     */
    public function afterSave()
    {
        $this->saveOptionsAndInventories();
    }

    /**
     * After validate.
     *
     * @return void
     */
    public function afterValidate()
    {
        $this->validateOptionsAndInventories();
    }

    /**
     * Before save.
     *
     * @return void
     */
    public function beforeSave()
    {
        $this->setPlainDescription();
    }

    /**
     * Save related inventories.
     *
     * @param  array $inventories
     * @return void
     */
    protected function saveInventories($inventories)
    {
        foreach ($inventories as $inventory) {
            $model = Inventory::findOrFail($inventory['id']);

            if (array_key_exists('_deleted', $inventory) && $inventory['_deleted']) {
                $model->delete();
            } else {
                // print_r ($inventory);
                $model->fill($inventory);
                $model->product_id = $this->id;
                $model->save();
            }
        }
    }

    /**
     * Save related options.
     *
     * @param  array $options
     * @return void
     */
    protected function saveOptions($options)
    {
        foreach ($options as $index => $option) {
            $model = Option::findOrFail($option['id']);

            if (array_key_exists('_deleted', $option) && $option['_deleted']) {
                $model->delete();
            } else {
                $model->fill($option);
                $model->product_id = $this->id;
                $model->sort_order = $index;

                $model->save();
            }
        }
    }

    /**
     * Save related options and inventories.
     *
     * @return void
     */
    protected function saveOptionsAndInventories()
    {
        if (! $data = $this->getOriginalPurgeValue('options_inventories')) {
            return;
        }

        $data = json_decode($data, true);
        $this->saveOptions($data['options']);
        $this->saveInventories($data['inventories']);
    }

    /**
     * Set the plain text description_html.
     *
     * @return void
     */
    protected function setPlainDescription()
    {
        $this->description_plain = strip_tags($this->description_html);
    }

    /**
     * This exists to makes statuses sortable by assigning them a value.
     *
     * Disabled 0
     * Enabled  1
     *
     * @param  \October\Rain\Database\Builder   $query
     * @return \October\Rain\Database\Builder
     */
    public function scopeSelectStatus($query)
    {
        $grammar = $query->getQuery()->getGrammar();
        $price = $grammar->wrap('price');
        $inventory = $grammar->wrap('inventory');
        $is_enabled = $grammar->wrap('bedard_shop_products.is_enabled');
        $base_price = $grammar->wrap('bedard_shop_products.base_price');

        $subquery = 'CASE '.
            "WHEN {$is_enabled} = 1 THEN 1 ".
            'ELSE 0 '.
        'END';

        return $query->selectSubquery($subquery, 'status');
    }

    /**
     * Validate inventories.
     *
     * @param  array $inventories
     * @return void
     */
    protected function validateInventories($inventories)
    {
        // @todo
    }

    /**
     * Validate a product's options.
     *
     * @param  array $options
     * @return void
     */
    protected function validateOptions($options)
    {
        // validate each option individually
        foreach ($options as $option) {
            $model = new Option($option);
            $model->validate();
        }

        // @todo: prevent duplicate options
    }

    /**
     * Call validate for options and inventories.
     *
     * @return void
     */
    protected function validateOptionsAndInventories()
    {
        if (! $data = $this->getOriginalPurgeValue('options_inventories')) {
            return;
        }

        $data = json_decode($data, true);
        $this->validateOptions($data['options']);
        $this->validateInventories($data['inventories']);
    }
}
