<?php namespace Bedard\Shop\Models;

use Model;

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
        'price' => 0,
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
        'price',
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
    ];

    /**
     * @var array Validation
     */
    public $rules = [
        'name' => 'required',
        'price' => 'required|numeric|min:0',
        'slug' => 'required|unique:bedard_shop_products',
    ];

    /**
     * After save.
     *
     * @return void
     */
    public function afterSave()
    {
        $this->saveCategoryRelationships();
    }

    /**
     * Get the categories options.
     *
     * @return array
     */
    public function getCategoriesListOptions()
    {
        return Category::orderBy('name', 'asc')->lists('name', 'id');
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
     * Sync the categories checkboxlist with the category relationship.
     *
     * @return void
     */
    public function saveCategoryRelationships()
    {
        $categoryIds = $this->getOriginalPurgeValue('categoriesList');
        $this->categories()->sync($categoryIds);
    }
}
