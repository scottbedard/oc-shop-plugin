<?php namespace Bedard\Shop\Models;

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
        // If this category does not exist, anyone may be the parent
        if (! $this->exists) {
            return self::lists('name', 'id');
        }

        // Otherwise, only show valid potential parents
        return self::where('id', '<>', $this->id)->isNotChildOf($this->id)->lists('name', 'id');
    }

    /**
     * Itterate over categories and update them with the given values
     *
     * @param  array    $data
     * @return void
     */
    public static function updateMany(array $categories)
    {
        foreach ($categories as $category) {
            $id = $category['id'];
            unset($category['id']);
            Category::whereId($id)->update($category);
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
