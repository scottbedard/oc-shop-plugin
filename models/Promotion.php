<?php namespace Bedard\Shop\Models;

use Model;

/**
 * Promotion Model.
 */
class Promotion extends Model
{
    use \Bedard\Shop\Traits\Amountable,
        \Bedard\Shop\Traits\StartEndable,
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
        'is_percentage' => true,
        'minimum_cart_value' => 0,
    ];

    /**
     * @var array Attribute casting
     */
    public $casts = [
        'amount' => 'float',
        'is_percentage' => 'boolean',
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
        'is_percentage',
        'message',
        'minimum_cart_value',
        'name',
    ];

    /**
     * @var array Purgeable vields
     */
    protected $purgeable = [];

    /**
     * @var  array Validation rules
     */
    public $rules = [
        'end_at' => 'date',
        'minimum_cart_value' => 'numeric|min:0',
        'name' => 'required',
        'start_at' => 'date',
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
}
