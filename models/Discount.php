<?php namespace Bedard\Shop\Models;

use Flash;
use Lang;
use Model;
use October\Rain\Database\ModelException;

/**
 * Discount Model.
 */
class Discount extends Model
{
    use \October\Rain\Database\Traits\Purgeable,
        \October\Rain\Database\Traits\Validation;

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
     * @var array Attribute casting
     */
    protected $casts = [
        //
    ];

    /**
     * @var array Date casting
     */
    protected $dates = [
        'end_at',
        'start_at',
    ];

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'amount_exact',
        'amount_percentage',
        'end_at',
        'is_percentage',
        'name',
        'start_at',
    ];

    /**
     * @var array Purgeable vields
     */
    protected $purgeable = [
        'amount_exact',
        'amount_percentage',
    ];

    /**
     * @var array Relations
     */
    public $hasMany = [];
    public $morphMany = [];

    /**
     * @var  array Validation rules
     */
    public $rules = [
        'end_at' => 'date',
        'name' => 'required',
        'start_at' => 'date',
    ];

    /**
     * After validate.
     *
     * @return void
     */
    public function afterValidate()
    {
        $this->validateDates();
    }

    /**
     * Filter form fields.
     *
     * @param  object   $fields
     * @return void
     */
    public function filterFields($fields)
    {
        $fields->amount_exact->hidden = $this->is_percentage;
        $fields->amount_percentage->hidden = ! $this->is_percentage;
    }

    /**
     * Ensure the start and end dates are valid
     *
     * @return void
     */
    public function validateDates()
    {
        // Start date must be after the end date
        if ($this->start_at !== null &&
            $this->end_at !== null &&
            $this->start_at >= $this->end_at) {
            Flash::error(Lang::get('bedard.shop::lang.discounts.form.start_at_invalid'));
            throw new ModelException($this);
        }
    }
}
