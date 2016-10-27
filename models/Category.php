<?php namespace Bedard\Shop\Models;

use Lang;
use Model;

/**
 * Category Model.
 */
class Category extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_shop_categories';

    /**
     * @var array Default attributes
     */
    public $attributes = [
        'description_html' => '',
        'description_plain' => '',
    ];

    /**
     * @var array Attribute casting
     */
    protected $casts = [
        'is_active' => 'boolean',
        'is_visible' => 'boolean',
    ];

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'is_active',
        'is_visible',
        'description_html',
        'description_plain',
        'name',
        'parent_id',
        'slug',
    ];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'parent' => 'Bedard\Shop\Models\Category',
    ];

    public $belongsToMany = [
        'discounts' => [
            'Bedard\Shop\Models\Discount',
            'table' => 'bedard_shop_category_discount',
        ],
        'products' => [
            'Bedard\Shop\Models\Product',
            'table' => 'bedard_shop_category_product',
            'pivot' => ['is_inherited'],
        ],
    ];

    /**
     * @var array Validation
     */
    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique:bedard_shop_categories',
    ];

    /**
     * After save.
     *
     * @return void
     */
    public function afterSave()
    {
        $this->syncInheritedProducts();
    }

    /**
     * Before save.
     *
     * @return void
     */
    public function beforeSave()
    {
        $this->setPlainDescription();
        $this->setNullParentId();
    }

    /**
     * Get the parent IDs of every category
     *
     * @return array
     */
    public static function getParentCategoryIds() {
        $categories = self::select('id', 'parent_id')->get();

        $tree = [];
        foreach ($categories as $category) {
            $tree[$category->id] = self::walkParentCategories($categories, $category);
        }

        return $tree;
    }

    /**
     * Find the child ids of a parent category.
     *
     * @param  \Bedard\Shop\Models\Category|int     $parent
     * @param  \October\Rain\Database\Collection    $categories
     * @return array
     */
    public static function getChildIds($parent, \October\Rain\Database\Collection $categories = null)
    {
        if ($categories === null) {
            $categories = self::whereNotNull('parent_id')
                ->where('id', '<>', $parent)
                ->select('id', 'parent_id')
                ->get();
        }

        $children = [];
        foreach ($categories as $category) {
            if ($category->parent_id == $parent) {
                $children[] = $category->id;
                $children = array_merge($children, self::getChildIds($category->id, $categories));
            }
        }

        return $children;
    }

    public static function getParentIds($children, \October\Rain\Database\Collection $categories = null)
    {
        if (! is_array($children)) {
            $children = [$children];
        }

        if ($categories === null) {
            $categories = self::select('id', 'parent_id')->get();
        }

        $parents = [];
        foreach ($children as $child) {
            $category = $categories->filter(function ($model) use ($child) {
                return $model->id == $child;
            })->first();

            while ($category && $category->parent_id) {
                $parents[] = $category->parent_id;
                $category = $categories->filter(function ($model) use ($category) {
                    return $model->id == $category->parent_id;
                })->first();
            }
        }

        return array_unique($parents);
    }

    /**
     * Get options for the parent form field.
     *
     * @return array
     */
    public function getParentIdOptions()
    {
        $options = $this->exists
            ? self::where('id', '<>', $this->id)->isNotChildOf($this->id)->orderBy('name')->lists('name', 'id')
            : self::orderBy('name')->lists('name', 'id');

        array_unshift($options, '<em>'.Lang::get('bedard.shop::lang.categories.form.no_parent').'</em>');

        return $options;
    }

    /**
     * Query categories that are children of another category.
     *
     * @param  \October\Rain\Database\Builder   $query
     * @param  \Bedard\Shop\Models\Category|int $parent
     * @return \October\Rain\Database\Builder
     */
    public function scopeIsChildOf($query, $parent)
    {
        return $query->whereIn('id', self::getChildIds($parent));
    }

    /**
     * Query categories that are not children of another category.
     *
     * @param  \October\Rain\Database\Builder   $query
     * @param  \Bedard\Shop\Models\Category|int $parent
     * @return \October\Rain\Database\Builder
     */
    public function scopeIsNotChildOf($query, $parent)
    {
        return $query->whereNotIn('id', self::getChildIds($parent));
    }

    /**
     * Query categories that are a parent of another category.
     *
     * @param  \October\Rain\Database\Builder   $query
     * @param  int                              $child
     * @return \October\Rain\Database\Builder
     */
    public function scopeIsParentOf($query, $child)
    {
        return $query->whereIn('id', self::getParentIds($child));
    }

    /**
     * Query categories that are not a parent of another category.
     *
     * @param  \October\Rain\Database\Builder   $query
     * @param  int                              $child
     * @return \October\Rain\Database\Builder
     */
    public function scopeIsNotParentOf($query, $child)
    {
        return $query->whereNotIn('id', self::getParentIds($child));
    }

    /**
     * Convert falsey parent id values to null.
     *
     * @return void
     */
    public function setNullParentId()
    {
        if (! $this->parent_id) {
            $this->parent_id = null;
        }
    }

    /**
     * Set the plain text description_html.
     *
     * @return void
     */
    public function setPlainDescription()
    {
        $this->description_plain = strip_tags($this->description_html);
    }

    /**
     * If the parent id has changed, resync all products.
     *
     * @return void
     */
    public function syncInheritedProducts()
    {
        if ($this->isDirty('parent_id')) {
            Product::syncAllInheritedCategories();
        }
    }

    /**
     * Itterate over categories and update them with the given values.
     *
     * @param  array    $categories
     * @return void
     */
    public static function updateMany(array $categories)
    {
        foreach ($categories as $category) {
            $id = $category['id'];
            unset($category['id']);
            self::whereId($id)->update($category);
        }
    }

    /**
     * Walk up the category tree gathering parent IDs.
     *
     * @param  \October\Rain\Database\Collection    $categories     All categories
     * @param  \Bedard\Shop\Models\Category         $category       Current category being walked over
     * @param  array                                $tree           Tree of parent IDs
     * @return arrau
     */
    public static function walkParentCategories($categories, $category, $tree = [])
    {
        if ($category->parent_id !== null) {
            $tree[] = $category->parent_id;
            $tree = array_merge($tree, self::walkParentCategories($categories, $categories->find($category->parent_id)));
        }

        return $tree;
    }
}
