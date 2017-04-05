<?php namespace Bedard\Shop\Models;

use Model;

/**
 * Category Model.
 */
class Category extends Model
{
    use \October\Rain\Database\Traits\NestedTree;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_shop_categories';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'description_html',
        'name',
        'parent_id',
        'slug',
    ];

    /**
     * @var array Relations
     */
    public $belongsToMany = [
        'product_count' => [
            'Bedard\Shop\Models\Product',
            'count' => true,
            'scope' => 'isEnabled',
            'table' => 'bedard_shop_category_product',
        ],
        'products' => [
            'Bedard\Shop\Models\Product',
            'pivot' => ['is_inherited'],
            'scope' => 'isEnabled',
            'table' => 'bedard_shop_category_product',
        ],
    ];

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
     * Get the parent ids of a given category.
     *
     * @param  integer                                  $id
     * @param  October\Rain\Database\Collection|null    $categories
     * @return array
     */
    public static function getParentIds($id, $categories = null)
    {
        if (! $categories) {
            $categories = self::all();
        }

        $ancestors = [];
        $category = $categories->find($id);
        while ($category && $category->parent_id) {
            $ancestors[] = $category->parent_id;
            $category = $categories->find($category->parent_id);
        }

        return $ancestors;
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
}
