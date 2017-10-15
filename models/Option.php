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
        $this->savePendingValues();
    }

    /**
     * Create new values.
     *
     * @param  array|null $values
     * @return void
     */
    protected function createNewValues(array $values = null)
    {
        if ($values === null) {
            return;
        }

        // extract our new options from the options being updated
        $newValues = array_filter($values, function ($value) {
            return $value['id'] === null && ! $value['_delete'];
        }, ARRAY_FILTER_USE_BOTH);

        // create a model for each one and relate it to this option
        foreach ($newValues as $id => $newValue) {
            $value = new OptionValue;
            $value->option_id = $this->id;
            $value->fill($newValue);
            $value->save();
        }
    }

    protected function deleteFlaggedValues(array $values = null)
    {
        if ($values === null) {
            return;
        }

        // extract our new options from the options being updated
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
    public function savePendingValues()
    {
        $values = $this->getOriginalPurgeValue('pending_values');

        $this->deleteFlaggedValues($values);
        $this->createNewValues($values);
        $this->updateExistingValues($values);
    }

    protected function updateExistingValues(array $values = null)
    {
        if ($values === null) {
            return;
        }

        $existingValues = array_filter($values, function ($value) {
            return $value['id'] !== null && ! $value['_delete'];
        }, ARRAY_FILTER_USE_BOTH);

        foreach ($existingValues as $id => $data) {
            $value = OptionValue::find($data['id']);
            $value->fill($data);
            $value->save();
        }
    }
}
