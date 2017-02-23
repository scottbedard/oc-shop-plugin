<?php namespace Bedard\Shop\Models;

use Lang;
use Model;
use Exception;

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
        'value_data',
    ];

    /**
     * @var array Purgeable fields
     */
    public $purgeable = [
        'value_data',
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
        $this->saveValues();
    }

    /**
     * Before save.
     *
     * @return void
     */
    public function beforeSave()
    {
        $this->validateValues();
    }

    /**
     * Save related values.
     *
     * @return void
     */
    protected function saveValues()
    {
        $values = $this->getOriginalPurgeValue('value_data') ?: [];

        if (count($values)) {
            foreach ($values as $value) {
                $model = $value['id'] !== null
                    ? OptionValue::findOrNew($value['id'])
                    : new OptionValue;

                $model->name = $value['name'];
                $model->option_id = $this->id;
                $model->sort_order = $value['sort_order'];
                $model->save();
            }
        }
    }

    /**
     * Validate option values.
     *
     * @return void
     */
    protected function validateValues()
    {
        $names = [];
        $values = $this->getOriginalPurgeValue('value_data') ?: [];

        foreach ($values as $value) {
            // validate each value individually
            $model = new OptionValue($value);
            $model->validate();

            // ensure that the name is unique to this option
            if (in_array($value['name'], $names)) {
                throw new Exception(Lang::get('bedard.shop::lang.options.form.values_unique'));
            }

            $names[] = $value['name'];
        }

        // ensure that at least one value was provided
        if (count($names) < 1) {
            throw new Exception(Lang::get('bedard.shop::lang.options.form.values_required'));
        }
    }
}
