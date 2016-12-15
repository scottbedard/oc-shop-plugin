<?php namespace Bedard\Shop\Models;

use Model;

/**
 * Promotion Model.
 */
class Promotion extends Model
{
    use \Bedard\Shop\Traits\StartEndable,
        \Bedard\Shop\Traits\Timeable,
        \October\Rain\Database\Traits\Purgeable,
        \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_shop_promotions';

    /**
     * @var array Default attributes
     */
    public $attributes = [
        'amount' => 0,
        'amount_exact' => 0,
        'amount_percentage' => 0,
        'is_percentage' => true,
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
        'amount',
        'is_percentage',
        'message',
        'minimum_cart_value',
        'name',
    ];

    /**
     * @var array Purgeable vields
     */
    protected $purgeable = [
        'amount_exact',
        'amount_percentage',
    ];

    /**
     * @var  array Validation rules
     */
    public $rules = [
        'end_at' => 'date',
        'name' => 'required',
        'start_at' => 'date',
        'amount_exact' => 'numeric|min:0',
        'amount_percentage' => 'numeric|min:0|max:100',
    ];

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
     * Set the promotion amount.
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
}
