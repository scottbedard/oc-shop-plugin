<?php namespace Bedard\Shop\Models;

use Carbon\Carbon;
use Flash;
use Lang;
use Model;
use October\Rain\Database\ModelException;

/**
 * Discount Model.
 */
class Discount extends Model
{
    use \Bedard\Shop\Traits\Subqueryable,
        \October\Rain\Database\Traits\Purgeable,
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
        'amount',
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
        'amount_exact' => 'numeric|min:0',
        'amount_percentage' => 'integer|min:0',
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
     * Before save.
     *
     * @return void
     */
    public function beforeSave()
    {
        $this->setAmount();
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
     * Query discounts that are not expired.
     *
     * @param  [type] $query [description]
     * @return [type]        [description]
     */
    public function scopeIsNotExpired($query)
    {
        return $query->where(function($discount) {
            return $discount->whereNull('end_at')
                ->orWhere('end_at', '>', (string) Carbon::now());
        });
    }

    /**
     * This exists to makes statuses sortable by assigning them a value.
     *
     * Expired  0
     * Running  1
     * Upcoming 2
     *
     * @param  \October\Rain\Database\Builder   $query
     * @return \October\Rain\Database\Builder
     */
    public function scopeSelectStatus($query)
    {
        $grammar = $query->getQuery()->getGrammar();
        $start_at = $grammar->wrap($this->table.'.start_at');
        $end_at = $grammar->wrap($this->table.'.end_at');
        $now = Carbon::now();

        $subquery = 'CASE '.
            "WHEN ({$end_at} IS NOT NULL AND {$end_at} < '{$now}') THEN 0 ".
            "WHEN ({$start_at} IS NOT NULL AND {$start_at} > '{$now}') THEN 2 ".
            'ELSE 1 '.
        'END';

        return $query->selectSubquery($subquery, 'status');
    }

    /**
     * Set the discount amount.
     *
     * @return  void
     */
    public function setAmount()
    {
        $exact = $this->getOriginalPurgeValue('amount_exact');
        $percentage = $this->getOriginalPurgeValue('amount_percentage');

        $this->amount = $this->is_percentage
            ? $percentage
            : $exact;
    }

    /**
     * Ensure the start and end dates are valid.
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
