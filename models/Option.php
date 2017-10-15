<?php namespace Bedard\Shop\Models;

use Model;

/**
 * Option Model.
 */
class Option extends Model
{
    use \October\Rain\Database\Traits\Purgeable,
        \October\Rain\Database\Traits\Validation;

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
     * @var array Attribute casting
     */
    protected $casts = [
        'id' => 'integer',
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
        'pending_values',
    ];

    /**
     * @var array Purgeable fields
     */
    public $purgeable = [
        'pending_values',
    ];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'product' => [
            'Bedard\Shop\Models\Product',
        ],
    ];

    public $hasMany = [
        'values' => [
            'Bedard\Shop\Models\OptionValue',
            'delete' => true,
            'order' => 'sort_order asc',
        ],
    ];

    /**
     * @var array Validation
     */
    public $rules = [
        'name' => 'required',
    ];

    /**
     * After save.
     *
     * @return void
     */
    public function afterSave()
    {
        $this->processPendingValues();
    }

    /**
     * Delete option values.
     *
     * @param  array $values
     * @return void
     */
    protected function deleteFlaggedValues(array $values = null)
    {
        $flaggedValues = array_filter($values, function ($value) {
            return $value['_delete'];
        }, ARRAY_FILTER_USE_BOTH);

        foreach ($flaggedValues as $id => $data) {
            $value = OptionValue::find($data['id']);

            if ($value) {
                $value->delete();
            }
        }
    }

    /**
     * Save related values.
     *
     * @return void
     */
    public function processPendingValues()
    {
        $values = $this->getOriginalPurgeValue('pending_values');

        if ($values) {
            $this->deleteFlaggedValues($values);
            $this->saveValues($values);
        }
    }

    /**
     * Save option values.
     *
     * @param  array  $values
     * @return void
     */
    protected function saveValues(array $values)
    {
        $savedValues = array_filter($values, function ($value) {
            return ! $value['_delete'];
        }, ARRAY_FILTER_USE_BOTH);

        foreach ($savedValues as $index => $data) {
            $value = $data['id']
                ? OptionValue::findOrNew($data['id'])
                : new OptionValue;

            $value->fill($data);
            $value->sort_order = $index;
            $value->option_id = $this->id;
            $value->save();
        }
    }
}
