<?php namespace Bedard\Shop\Models;

use Model;

/**
 * Category Model
 */
class Category extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_shop_categories';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    protected $fillable = [
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

    /**
     * @var array Validation
     */
    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique:bedard_shop_categories',
    ];

    public static function getChildIds($categories, $parent) {
        if (gettype($parent) === 'object') {
            $parent = $parent->id;
        }

        $children = [];
        foreach ($categories as $category) {
            if ($category->parent_id == $parent) {
                $children[] = $category->id;
                $children = array_merge($children, self::getChildIds($categories, $category->id));
            }
        }

        return $children;
    }

    /**
     * Get options for the parent form field
     *
     * @return array
     */
    public function getParentIdOptions() {
        return self::get()->lists('name', 'id');
    }

    /**
     * Query categories that are children of another category
     *
     * @param  \October\Rain\Database\Builder       $query
     * @param  \Bedard\Shop\Models\Category|integer $parent
     * @return \October\Rain\Database\Builder
     */
    public function scopeIsChildOf($query, $parent)
    {
        $categories = self::select('id', 'parent_id')->get();
        return $query->whereIn('id', self::getChildIds($categories, $parent));
    }

    /**
     * Query categories that are not children of another category
     *
     * @param  \October\Rain\Database\Builder       $query
     * @param  \Bedard\Shop\Models\Category|integer $parent
     * @return \October\Rain\Database\Builder
     */
    public function scopeIsNotChildOf($query, $parent)
    {
        $categories = self::select('id', 'parent_id')->get();
        return $query->whereNotIn('id', self::getChildIds($categories, $parent));
    }

}
