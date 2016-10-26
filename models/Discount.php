<?php namespace Bedard\Shop\Models;

use Model;

/**
 * Discount Model.
 */
class Discount extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_shop_discounts';

    /**
     * @var array Default attributes
     */
    public $attributes = [
        'is_percentage' => true,
    ];

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
    public $hasOne = [];
    public $hasMany = [];
    public $belongsTo = [];
    public $belongsToMany = [];
    public $morphTo = [];
    public $morphOne = [];
    public $morphMany = [];
    public $attachOne = [];
    public $attachMany = [];

    /**
     * Filter form fields
     *
     * @param  object   $fields
     * @return void
     */
    public function filterFields($fields)
    {
        $fields->amount_exact->hidden = $this->is_percentage;
        $fields->amount_percentage->hidden = !$this->is_percentage;
    }
}
