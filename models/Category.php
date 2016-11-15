<?php namespace Bedard\Shop\Models;

use Lang;
use Model;
use October\Rain\Database\Builder;

/**
 * Category Model.
 */
class Category extends Model
{
    use \October\Rain\Database\Traits\Purgeable,
        \October\Rain\Database\Traits\Validation;

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
        'product_columns' => 4,
        'product_order' => '',
        'product_rows' => 3,
        'product_sort' => 'created_at:desc',
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
     * @var array Jsonable fields
     */
    protected $jsonable = [
        'product_order',
    ];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'is_active',
        'is_visible',
        'category_filters',
        'description_html',
        'description_plain',
        'name',
        'parent_id',
        'product_order',
        'product_sort',
        'product_sort_column',
        'product_sort_direction',
        'slug',
    ];

    /**
     * @var array Purgeable fields
     */
    protected $purgeable = [
        'category_filters',
        'filters_list',
        'product_sort',
    ];

    /**
     * @var array Relations
     */
    public $attachMany = [
        'thumbnails' => 'System\Models\File',
    ];

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

    public $hasMany = [
        'filters' => [
            'Bedard\Shop\Models\Filter',
        ],
    ];

    /**
     * @var array Validation
     */
    public $rules = [
        'name' => 'required',
        'product_columns' => 'required|integer|min:1',
        'product_rows' => 'required|integer|min:0',
        'slug' => 'required|unique:bedard_shop_categories',
    ];

    /**
     * After delete.
     *
     * @return void
     */
    public function afterDelete()
    {
        $this->syncInheritedProductsAfterDelete();
    }

    /**
     * After save.
     *
     * @return void
     */
    public function afterSave()
    {
        $this->saveFiltersRelationship();
        $this->syncInheritedProductsAfterSave();
    }

    /**
     * Apply filters to a products query.
     *
     * @param  \October\Rain\Database\Builder   $products
     * @return void
     */
    protected function applyProductFilters(Builder &$products)
    {
        $products->where(function ($query) {
            foreach ($this->filters as $filter) {
                $right = $filter->getRightClause();
                $query->where($filter->left, $filter->comparator, $right);
            }
        });
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
        $this->setProductSort();
    }

    /**
     * Delete filters that have the is_deleted flag.
     *
     * @param  array $filters
     * @return array
     */
    protected function deleteRelatedFilters(array $filters)
    {
        return array_filter($filters, function ($filter) {
            if ($filter['id'] !== null && $filter['is_deleted']) {
                Filter::find($filter['id'])->delete();
            }

            return ! $filter['is_deleted'];
        });
    }

    /**
     * Get the parent IDs of every category.
     *
     * @return array
     */
    public static function getParentCategoryIds()
    {
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
     * Query a category's products.
     *
     * @param  array $params
     * @return \October\Rain\Database\Collection
     */
    public function getProducts(array $params = [])
    {
        $products = Product::isEnabled();

        // select the appropriate columns
        if (array_key_exists('products_select', $params) && $params['products_select']) {
            $products->select($params['products_select']);

            if (in_array('price', $params['products_select'])) {
                $products->joinPrice();
            }
        }

        // grab products from filters or relationship
        if ($this->isFiltered()) {
            $this->applyProductFilters($products);
        } else {
            $products->appearingInCategory($this->id);
        }

        // sort the results
        if (array_key_exists('products_sort_column', $params) &&
            array_key_exists('products_sort_direction', $params)) {
            $products->orderBy($params['products_sort_column'], $params['products_sort_direction']);
        } elseif ($this->isCustomSorted()) {
            // @todo
        } else {
            $products->orderBy($this->product_sort_column, $this->product_sort_direction);
        }

        return $products->get();
    }

    /**
     * Determine if the category has a custom sort.
     *
     * @return bool
     */
    public function isCustomSorted()
    {
        return ! $this->product_sort_column && ! $this->product_sort_direction;
    }

    /**
     * Determine if the category is filtered.
     *
     * @return bool
     */
    public function isFiltered()
    {
        return $this->filters->count() > 0;
    }

    /**
     * Save filters relationship.
     *
     * @return void
     */
    protected function saveFiltersRelationship()
    {
        $filters = $this->getOriginalPurgeValue('category_filters');

        if (is_array($filters) && ! empty($filters)) {
            $filters = $this->deleteRelatedFilters($filters);
            $this->saveRelatedFilters($filters);
        }
    }

    /**
     * Save related filters.
     *
     * @param  array  $filters
     * @return void
     */
    protected function saveRelatedFilters(array $filters)
    {
        foreach ($filters as $filter) {
            $model = $filter['id'] !== null
                ? Filter::firstOrNew(['id' => $filter['id']])
                : new Filter;

            $filter['category_id'] = $this->id;
            $model->fill($filter);
            $model->save();
        }
    }

    /**
     * Query categories that are listed as active.
     *
     * @param  \October\Rain\Database\Builder   $query
     * @return \October\Rain\Database\Builder
     */
    public function scopeIsActive($query)
    {
        return $query->whereIsActive(true);
    }

    /**
     * Query categories that are listed as not active.
     *
     * @param  \October\Rain\Database\Builder   $query
     * @return \October\Rain\Database\Builder
     */
    public function scopeIsNotActive($query)
    {
        return $query->whereIsActive(false);
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
    protected function setNullParentId()
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
    protected function setPlainDescription()
    {
        $this->description_plain = strip_tags($this->description_html);
    }

    /**
     * Set the product sort.
     *
     * @return void
     */
    protected function setProductSort()
    {
        $sort = $this->getOriginalPurgeValue('product_sort');

        if ($sort === 'custom') {
            $this->product_sort_column = null;
            $this->product_sort_direction = null;
        } else {
            $parts = explode(':', $this->getOriginalPurgeValue('product_sort'));
            $this->product_sort_column = $parts[0];
            $this->product_sort_direction = $parts[1];
        }
    }

    /**
     * If the model wasn't deleted in bulk, sync inherited categories.
     *
     * @return void
     */
    public function syncInheritedProductsAfterDelete()
    {
        if (! $this->dontSyncAfterDelete) {
            Product::syncAllInheritedCategories();
        }
    }

    /**
     * If the parent id has changed, resync all products.
     *
     * @return void
     */
    public function syncInheritedProductsAfterSave()
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
        if ($category && $category->parent_id !== null) {
            $tree[] = $category->parent_id;
            $tree = array_merge($tree, self::walkParentCategories($categories, $categories->find($category->parent_id)));
        }

        return $tree;
    }
}
