<?php namespace Bedard\Shop\Models;

use Model;

/**
 * Address Model.
 */
class Address extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_shop_addresses';

    /**
     * @var array Default attributes
     */
    public $attributes = [
        'additional_data' => '',
    ];

    /**
     * @var array Attribute casting
     */
    protected $casts = [
        'is_billing' => 'boolean',
        'is_primary' => 'boolean',
        'is_shipping' => 'boolean',
    ];

    /**
     * @var array Jsonable fields
     */
    protected $jsonable = [
        'additional_data',
    ];

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'additional_data',
        'city',
        'country',
        'customer_id',
        'is_billing',
        'is_primary',
        'is_shipping',
        'line_1',
        'line_2',
        'line_3',
        'postcode',
        'province',
    ];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'customer' => [
            'Bedard\Shop\Models\Customer',
        ],
    ];

    /**
     * After save.
     *
     * @return void
     */
    public function afterSave()
    {
        $this->updatePrimaryAddresses();
    }

    /**
     * Select shipping addresses.
     *
     * @param  \October\Rain\Database\Builder   $query
     * @return \October\Rain\Database\Builder
     */
    public function scopeIsShipping($query)
    {
        return $query->whereIsShipping(true);
    }

    /**
     * Select billing addresses.
     *
     * @param  \October\Rain\Database\Builder   $query
     * @return \October\Rain\Database\Builder
     */
    public function scopeIsBilling($query)
    {
        return $query->whereIsBilling(true);
    }

    /**
     * Select primary addresses.
     *
     * @param  \October\Rain\Database\Builder   $query
     * @return \October\Rain\Database\Builder
     */
    public function scopeIsPrimary($query)
    {
        return $query->whereIsPrimary(true);
    }

    /**
     * Ensure that only one billing and shipping address may be primary.
     *
     * @return void
     */
    protected function updatePrimaryAddresses()
    {
        if (! $this->customer_id) {
            return;
        }

        $query = self::whereCustomerId($this->customer_id)
            ->where('id', '<>', $this->id)
            ->isPrimary();

        if ($this->is_shipping && $this->is_primary) {
            $shipping = $query->isShipping()->update(['is_primary' => false]);
        }

        if ($this->is_billing && $this->is_primary) {
            $shipping = $query->isBilling()->update(['is_primary' => false]);
        }
    }
}
