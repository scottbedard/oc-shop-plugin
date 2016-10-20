<?php namespace Bedard\Shop\Models;

use Model;

/**
 * Product Model.
 */
class Product extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_shop_products';

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
    public $belongsTo = [];

    /**
     * @var array Validation
     */
    public $rules = [
        'name' => 'required',
        'slug' => 'required|unique:bedard_shop_products',
    ];
}
