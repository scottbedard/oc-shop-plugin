<?php namespace Bedard\Shop\Models;

use Model;

/**
 * OptionValue Model.
 */
class OptionValue extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_shop_option_values';

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'option' => [
            'Bedard\Shop\Models\Option',
        ],
    ];
}
