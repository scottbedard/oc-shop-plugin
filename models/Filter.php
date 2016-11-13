<?php namespace Bedard\Shop\Models;

use Model;

/**
 * Filter Model
 */
class Filter extends Model
{

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
    ];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'category' => [
            'Bedard\Shop\Models\Category',
        ],
    ];

}
