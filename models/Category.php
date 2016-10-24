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
        'products' => [
            'Bedard\Shop\Models\Product',
            'table' => 'bedard_shop_category_product',
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
     * Find the child ids of a parent category.
     *
     * @param  \Bedard\Shop\Models\Category|int     $parent
     * @param  \October\Rain\Database\Collection    $categories
     * @return array
     */
    public static function getChildIds($parent, \October\Rain\Database\Collection $categories = null)
    {
        if (gettype($parent) === 'object') {
            $parent = $parent->id;
        }

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
     * Itterate over categories and update them with the given values.
     *
     * @param  array    $data
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
}