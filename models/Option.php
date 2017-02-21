<?php namespace Bedard\Shop\Models;

use Model;

/**
 * Option Model.
 */
class Option extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_shop_options';

    /**
     * @var array Default attributes
     */
    public $attributes = [
        'name' => '',
        'placeholder' => '',
        'sort_order' => 0,
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
        'placeholder',
        'sort_order',
    ];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'product' => [
            'Bedard\Shop\Models\Product',
        ],
    ];

    /**
     * @var array Validation
     */
    public $rules = [
        'name' => 'required',
    ];
}
