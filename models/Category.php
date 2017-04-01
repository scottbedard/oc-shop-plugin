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
     * Set the plain text description_html.
     *
     * @return void
     */
    protected function setPlainDescription()
    {
        $this->description_plain = strip_tags($this->description_html);
    }
}
