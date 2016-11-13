<?php namespace Bedard\Shop\Models;

use Model;

/**
 * Filter Model.
 */
class Filter extends Model
{
    use \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_shop_filters';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'comparator',
        'left',
        'right',
        'sort_order',
        'value',
    ];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'category' => [
            'Bedard\Shop\Models\Category',
        ],
    ];

    /**
     * @var Validation
     */
    public $rules = [
        'left' => 'required',
        'comparator' => 'required|min:1|max:2',
        'right' => 'required',
        'value' => 'numeric|required_if:right,custom',
    ];
}
