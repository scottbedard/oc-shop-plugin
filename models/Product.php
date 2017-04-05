<?php namespace Bedard\Shop\Models;

use DB;
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
        'base_price' => 'float',
        'id' => 'integer',
        'inventory_count' => 'integer',
        'is_enabled' => 'boolean',
        'price' => 'float',
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
        'categories_field',
        'options_inventories',
    ];

    /**
     * @var array Relations
     */
    public $attachMany = [
        'images' => [
            'System\Models\File',
        ],
        'thumbnails' => [
            'System\Models\File',
        ],
    ];

    public $belongsToMany = [
        'categories' => [
            'Bedard\Shop\Models\Category',
            'pivot' => ['is_inherited'],
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
        $this->saveCategories();
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
     * Determine if the categories field contains new values.
     *
     * @return bool
     */
    protected function categoriesAreChanged($new)
    {
        $old = $this->categoriesField ?: [];
        sort($old);
        sort($new);

        return ! count($old) || ! count($new) || $old != $new;
    }

    /**
     * Format the price.
     *
     * @return string
     */
    public function formattedPrice()
    {
        return number_format($this->base_price, 2);
    }

    /**
     * Get the categories that are not inherited.
     *
     * @return array
     */
    public function getCategoriesFieldAttribute()
    {
        return $this->categories()
            ->wherePivot('is_inherited', false)
            ->lists('id');
    }

    /**
     * List the category field options.
     *
     * @return array
     */
    public function getCategoriesFieldOptions()
    {
        return Category::get()->sortBy('name')->lists('name', 'id');
    }

    /**
     * Save related categories.
     *
     * @return void
     */
    protected function saveCategories()
    {
        // if the categories haven't changed, do nothing
        $directIds = $this->getOriginalPurgeValue('categories_field') ?: [];
        if (! $this->categoriesAreChanged($directIds)) {
            return;
        }

        // otherwise lets sync our categories. we first need to gather
        // the neccessary information, and create a few containers.
        $sync = [];
        $ancestorIds = [];
        $allCategories = Category::all();

        // iterate over our direct ids
        foreach ($directIds as $directCategoryId) {
            // keep track of our direct ids for the eventual sync call.
            // we need to provide the pivot data, because by default
            // the sync function won't existing database entries.
            $sync[$directCategoryId] = ['is_inherited' => 0];

            // add all of our direct category's parent ids to our ancestors
            $branchIds = $allCategories
                ->find($directCategoryId)
                ->getParents()
                ->lists('id');

            $ancestorIds = array_merge($ancestorIds, $branchIds);
        }

        // iterate over our ancestor ids and set their is_inherited flag
        foreach ($ancestorIds as $ancestorId) {
            $sync[$ancestorId] = [
                'is_inherited' => in_array($ancestorId, $directIds) ? 0 : 1,
            ];
        }

        // finally, sync our direct and ancestor categories
        $this->categories()->sync($sync);
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
            if ($model = Inventory::find($inventory['id'])) {
                if (array_key_exists('_deleted', $inventory) && $inventory['_deleted']) {
                    $model->delete();
                } else {
                    $model->fill($inventory);
                    $model->product_id = $this->id;
                    $model->save();
                }
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
            if ($model = Option::find($option['id'])) {
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
     * Fetch products in particular categories.
     *
     * @param  \October\Rain\Database\Builder   $query
     * @param  array|string                     $slugs
     * @return \October\Rain\Database\Builder
     */
    public function scopeInCategories($query, $slugs)
    {
        // if the slugs are a csv, explode them into an array
        if (is_string($slugs)) {
            $slugs = explode(',', $slugs);
        }

        return $query->whereHas('categories', function ($category) use ($slugs) {
            $category->whereIn('slug', $slugs);
        });
    }

    /**
     * Select products that are enabled.
     *
     * @param  \October\Rain\Database\Builder   $query
     * @return \October\Rain\Database\Builder
     */
    public function scopeIsEnabled($query)
    {
        return $query->whereIsEnabled(true);
    }

    /**
     * Joins the sum of available inventories.
     *
     * @param  \October\Rain\Database\Builder   $query
     * @return \October\Rain\Database\Builder
     */
    public function scopeJoinInventoryCount($query)
    {
        $alias = 'inventories';
        $grammar = $query->getQuery()->getGrammar();

        $subquery = Inventory::addSelect('bedard_shop_inventories.product_id')
            ->selectRaw('SUM('.$grammar->wrap('bedard_shop_inventories.quantity').') as '.$grammar->wrap('inventory_count'))
            ->groupBy('bedard_shop_inventories.product_id');

        return $query
            ->addSelect($alias.'.inventory_count')
            ->joinSubquery($subquery, $alias, 'bedard_shop_products.id', '=', $alias.'.product_id', 'leftJoin');
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
     * Set the plain text description_html.
     *
     * @return void
     */
    protected function setPlainDescription()
    {
        $this->description_plain = strip_tags($this->description_html);
    }

    protected function syncAllCategories()
    {
        // delete all inherited categories
        DB::table('bedard_shop_category_product')
            ->whereIsInherited(true)
            ->delete();

        // figure out what our branches are
        $branches = [];
        $categories = Category::select('id', 'parent_id')->get();
        $categories->each(function ($category) use (&$branches, $categories) {
            $branches[$category->id] = Category::getParentIds($category->id, $categories);
        });

        // itterate over each product and build up an insert object
        $insert = [];
        $products = self::with('categories')->select('id')->get();
        foreach ($products as $product) {
            foreach ($product->categories as $category) {
                if (empty($branches[$category->id])) {
                    continue;
                }

                foreach ($branches[$category->id] as $ancestorId) {
                    $insert[] = [
                        'category_id' => $ancestorId,
                        'product_id' => $product->id,
                        'is_inherited' => true,
                    ];
                }
            }
        }

        // insert the new inherited categories
        DB::table('bedard_shop_category_product')->insert($insert);
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
