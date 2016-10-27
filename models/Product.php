<?php namespace Bedard\Shop\Models;

use DB;
use Model;
use Queue;

/**
 * Product Model.
 */
class Product extends Model
{
    use \October\Rain\Database\Traits\Purgeable,
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
        'name',
        'base_price',
        'slug',
    ];

    /**
     * @var array Purgeable fields
     */
    public $purgeable = [
        'categoriesList',
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
        'prices' => [
            'Bedard\Shop\Models\Price',
            'delete' => true,
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
    }

    /**
     * Attach a product to it's inherited categories.
     *
     * @param  array|null $categoryIds
     * @return void
     */
    public function attachInheritedCategories(array $categoryIds = null)
    {
        if ($categoryIds === null) {
            $categoryIds = $this->categories->lists('id');
        }

        foreach (Category::isParentOf($categoryIds)->lists('id') as $parentId) {
            $this->categories()->attach($parentId, ['is_inherited' => true]);
        }
    }

    /**
     * Detach a product from it's inherited categories.
     *
     * @return void
     */
    public function detachInheritedCategories()
    {
        DB::table('bedard_shop_category_product')
            ->where('product_id', $this->id)
            ->where('is_inherited', 1)
            ->delete();
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
            $this->syncInheritedCategories($categoryIds);
        }
    }

    /**
     * Sync the inherited categories of all products.
     *
     * @return void
     */
    public static function syncAllInheritedCategories()
    {
        foreach (self::lists('id') as $id) {
            Queue::push(function ($job) use ($id) {
                Product::findOrFail($id)->syncInheritedCategories();
                $job->delete();
            });
        }
    }

    /**
     * Sync a product with it's inherited categories.
     *
     * @param  array|null $categoryIds
     * @return void
     */
    public function syncInheritedCategories(array $categoryIds = null)
    {
        $this->detachInheritedCategories();
        $this->attachInheritedCategories($categoryIds);
    }
}
