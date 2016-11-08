<?php namespace Bedard\Shop\Models;

use DB;
use Flash;
use Lang;
use Model;
use October\Rain\Database\Builder;
use October\Rain\Database\ModelException;
use Queue;

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
    ];

    /**
     * @var array Attribute casting
     */
    protected $casts = [
        'price' => 'float',
        'base_price' => 'float',
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
        'categoriesList',
        'description_html',
        'description_plain',
        'name',
        'optionsInventories',
        'slug',
    ];

    /**
     * @var array Purgeable fields
     */
    public $purgeable = [
        'categoriesList',
        'optionsInventories',
    ];

    /**
     * @var array Relations
     */
    public $belongsToMany = [
        'categories' => [
            'Bedard\Shop\Models\Category',
            'table' => 'bedard_shop_category_product',
            'conditions' => 'is_inherited = 0',
        ],
        'discounts' => [
            'Bedard\Shop\Models\Discount',
            'table' => 'bedard_shop_discount_product',
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
        'prices' => [
            'Bedard\Shop\Models\Price',
            'delete' => true,
        ],
    ];

    public $hasOne = [
        'current_price' => [
            'Bedard\Shop\Models\Price',
            'scope' => 'isActive',
            'order' => 'price asc',
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
        $this->saveBasePrice();
        $this->saveCategoryRelationships();
        $this->saveOptionAndInventoryRelationships();
    }

    /**
     * After validate.
     *
     * @return void
     */
    public function afterValidate()
    {
        $this->validateOptions();
        $this->validateInventories();
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
     * Delete inventories that have the is_deleted flag.
     *
     * @param  array $inventories
     * @return array
     */
    protected function deleteRelatedInventories($inventories)
    {
        return array_filter($inventories, function ($inventory) {
            if ($inventory['id'] !== null && $inventory['is_deleted']) {
                Inventory::find($inventory['id'])->delete();
            }

            return ! $inventory['is_deleted'];
        });
    }

    /**
     * Delete options that have the is_deleted flag.
     *
     * @param  array $options
     * @return array
     */
    protected function deleteRelatedOptions($options)
    {
        return array_filter($options, function ($option) {
            if ($option['id'] !== null && $option['is_deleted']) {
                Option::find($option['id'])->delete();
            }

            return ! $option['is_deleted'];
        });
    }

    /**
     * Get the categories options.
     *
     * @return array
     */
    public function getCategoriesListOptions()
    {
        return Category::orderBy('name')->lists('name', 'id');
    }

    /**
     * Get the categories that are directly related to this product.
     *
     * @return void
     */
    public function getCategoriesListAttribute()
    {
        return $this->categories()->lists('id');
    }

    /**
     * Update of create the related base price model.
     *
     * @return void
     */
    public function saveBasePrice()
    {
        Price::updateOrCreate(
            ['product_id' => $this->id, 'discount_id' => null],
            ['price' => $this->base_price]
        );
    }

    /**
     * Sync the categories checkboxlist with the category relationship.
     *
     * @return void
     */
    public function saveCategoryRelationships()
    {
        $categoryIds = $this->getOriginalPurgeValue('categoriesList');

        if (is_array($categoryIds)) {
            $this->categories()->sync($categoryIds);
        }

        $this->syncInheritedCategories();
    }

    /**
     * Save the options and inventories.
     *
     * @return void
     */
    public function saveOptionAndInventoryRelationships()
    {
        $data = $this->getOriginalPurgeValue('optionsInventories');

        if (is_array($data['options'])) {
            $options = $data['options'];
            $options = $this->deleteRelatedOptions($options);
            $this->saveRelatedOptions($options);
        }

        if (is_array($data['inventories'])) {
            $inventories = $data['inventories'];
            $inventories = $this->deleteRelatedInventories($inventories);
            $this->saveRelatedInventories($inventories);
        }
    }

    /**
     * Save an array of realted inventories.
     *
     * @param  array  $inventories
     * @return array
     */
    protected function saveRelatedInventories(array $inventories)
    {
        foreach ($inventories as $inventory) {
            $model = $inventory['id'] !== null
                ? Inventory::firstOrNew(['id' => $inventory['id']])
                : new Inventory;

            $inventory['product_id'] = $this->id;
            $model->fill($inventory);
            $model->save();
        }
    }

    /**
     * Save an array of related options.
     *
     * @param  array  $options
     * @return array
     */
    protected function saveRelatedOptions(array $options)
    {
        foreach ($options as &$option) {
            $model = $option['id'] !== null
                ? Option::firstOrNew(['id' => $option['id']])
                : new Option;

            $model->product_id = $this->id;
            $model->fill($option);

            $sessionKey = uniqid('session_key', true);
            if (is_array($option['values'])) {
                $this->saveRelatedOptionValues($model, $option['values'], $sessionKey);
            }

            $model->save(null, $sessionKey);
        }

        return $options;
    }

    /**
     * Save related option values.
     *
     * @param  Option $option
     * @param  array  $values
     * @param  string $sessionKey
     * @return void
     */
    protected function saveRelatedOptionValues(Option &$option, array $values, $sessionKey)
    {
        foreach ($values as $value) {
            $model = $value['id'] !== null
                ? OptionValue::firstOrNew(['id' => $value['id']])
                : new OptionValue;

            $model->fill($value);
            $model->save();

            $option->values()->add($model, $sessionKey);
        }
    }

    /**
     * Left joins a subquery containing the product price.
     *
     * @param  \October\Rain\Database\Builder   $query
     * @return \October\Rain\Database\Builder
     */
    public function scopeJoinPrice(Builder $query)
    {
        $alias = 'prices';
        $grammar = $query->getQuery()->getGrammar();

        $subquery = Price::isActive()
            ->addselect('bedard_shop_prices.product_id')
            ->selectRaw('MIN('.$grammar->wrap('bedard_shop_prices.price').') as '.$grammar->wrap('price'))
            ->groupBy('bedard_shop_prices.product_id');

        return $query
            ->addSelect($alias.'.price')
            ->joinSubquery($subquery, $alias, 'bedard_shop_products.id', '=', $alias.'.product_id');
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
     * Sync the inherited categories of all products.
     *
     * @return void
     */
    public static function syncAllInheritedCategories()
    {
        Queue::push(function ($job) {
            $data = [];
            $products = Product::with('categories')->get();
            $categoryTree = Category::getParentCategoryIds();

            foreach ($products as $product) {
                $inheritedCategoryIds = [];
                foreach ($product->categories as $category) {
                    if (array_key_exists($category->id, $categoryTree)) {
                        $inheritedCategoryIds = array_merge($inheritedCategoryIds, $categoryTree[$category->id]);
                    }
                }

                foreach (array_unique($inheritedCategoryIds) as $categoryId) {
                    $data[] = [
                        'category_id' => $categoryId,
                        'product_id' => $product->id,
                        'is_inherited' => 1,
                    ];
                }
            }

            DB::table('bedard_shop_category_product')->whereIsInherited(1)->delete();
            DB::table('bedard_shop_category_product')->insert($data);
            Discount::syncAllPrices();

            $job->delete();
        });
    }

    /**
     * Sync a product with it's inherited categories.
     *
     * @return void
     */
    public function syncInheritedCategories()
    {
        $data = [];
        $categoryIds = $this->categories()->lists('id');
        $parentIds = Category::isParentOf($categoryIds)->lists('id');
        foreach ($parentIds as $parentId) {
            $data[] = [
                'category_id' => $parentId,
                'product_id' => $this->id,
                'is_inherited' => true,
            ];
        }

        DB::table('bedard_shop_category_product')->whereProductId($this->id)->whereIsInherited(1)->delete();
        DB::table('bedard_shop_category_product')->insert($data);

        $categoryScope = array_merge($categoryIds, $parentIds);
        Discount::syncProductPrice($this, $categoryScope);
    }

    /**
     * Validate inventories.
     *
     * @throws \October\Rain\Database\ModelException
     * @return void
     */
    protected function validateInventories()
    {
        if (! is_array($this->optionsInventories) ||
            ! is_array($this->optionsInventories['options'])) {
            return;
        }

        $takenValueCombinations = [];
        foreach ($this->optionsInventories['inventories'] as $inventory) {
            // validate the inventory
            $model = new Inventory($inventory);
            $model->validate();

            // validate that the value combinations are unique
            sort($inventory['valueIds']);
            $valueCombination = json_encode($inventory['valueIds']);

            if (in_array($valueCombination, $takenValueCombinations)) {
                Flash::error(Lang::get('bedard.shop::lang.products.form.duplicate_inventories_error'));
                throw new ModelException($this);
            }

            $takenValueCombinations[] = $valueCombination;
        }
    }

    /**
     * Validate options.
     *
     * @throws \October\Rain\Database\ModelException
     * @return void
     */
    protected function validateOptions()
    {
        if (! is_array($this->optionsInventories) ||
            ! is_array($this->optionsInventories['options'])) {
            return;
        }

        $names = [];
        foreach ($this->optionsInventories['options'] as $option) {
            // validate the option
            $model = new Option($option);
            $model->validate();

            // validate that names are unique
            $name = strtolower(trim($option['name']));

            if (in_array($name, $names)) {
                Flash::error(Lang::get('bedard.shop::lang.products.form.duplicate_options_error'));
                throw new ModelException($this);
            }

            $names[] = $name;
        }
    }
}
