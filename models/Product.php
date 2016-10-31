<?php namespace Bedard\Shop\Models;

use Bedard\Shop\Models\Inventory;
use Bedard\Shop\Models\Option;
use Bedard\Shop\Models\OptionValue;
use DB;
use Model;
use October\Rain\Database\Builder;
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
    ];

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'categoriesList',
        'name',
        'base_price',
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
     * Save the options and inventories
     *
     * @return void
     */
    public function saveOptionAndInventoryRelationships()
    {
        $data = $this->getOriginalPurgeValue('optionsInventories');

        if (is_array($data['options'])) {
            $options = $this->saveRelatedOptions($data['options']);
        }
    }

    /**
     * Save an array of related options
     *
     * @param  array  $options
     * @return array
     */
    protected function saveRelatedOptions(array $options) {
        foreach ($options as &$option) {
            $model = $option['id'] !== null
                ? Option::firstOrNew(['id' => $option['id']])
                : new Option;

            $model->product_id = $this->id;
            $model->fill($option);
            $model->save();

            $option['id'] = $model->id;

            if (is_array($option['values'])) {
                $this->saveRelatedOptionValues($model, $option['values']);
            }
        }

        return $options;
    }

    /**
     * Save related option values.
     *
     * @param  Option $option
     * @param  array  $values
     * @return void
     */
    protected function saveRelatedOptionValues(Option $option, array $values) {
        foreach ($values as $value) {
            $model = $value['id'] !== null
                ? OptionValue::firstOrNew(['id' => $value['id']])
                : new OptionValue;

            $model->option_id = $option->id;
            $model->fill($value);
            $model->save();
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
}
