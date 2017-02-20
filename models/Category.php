<?php namespace Bedard\Shop\Models;

use Model;

/**
 * Category Model.
 */
class Category extends Model
{
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
        'name',
        'slug',
    ];

    /**
     * @var array Relations
     */
    public $belongsToMany = [
        'products' => [
            'Bedard\Shop\Models\Product',
            'table' => 'bedard_shop_category_product',
        ],
    ];
}
