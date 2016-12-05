<?php namespace Bedard\Shop\Models;

use DB;
use Model;
use Carbon\Carbon;

/**
 * Filter Model.
 */
class Filter extends Model
{
    use \October\Rain\Database\Traits\Purgeable,
        \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_shop_filters';

    /**
     * @var array Attribute casting
     */
    protected $casts = [
        'value' => 'float',
    ];

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'category_id',
        'comparator',
        'left',
        'right',
        'value',
    ];

    /**
     * @var array Purgeable fields
     */
    protected $purgeable = [
        'is_deleted',
    ];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'category' => [
            'Bedard\Shop\Models\Category',
        ],
    ];

    /**
     * @var Validation
     */
    public $rules = [
        'left' => 'required',
        'comparator' => 'required|min:1|max:2',
        'right' => 'required',
        'value' => 'numeric|required_if:right,custom',
    ];

    /**
     * Get the right side of a filter's where clause.
     *
     * @return mixed
     */
    public function getRightClause()
    {
        if ($this->getType() === 'price') {
            return $this->right === 'custom'
                ? $this->value
                : DB::raw($this->right);
        }

        return Carbon::now()->subDays($this->value);
    }

    /**
     * Determine the type of filter.
     *
     * @return string
     */
    public function getType()
    {
        return $this->left === 'base_price' || $this->left === 'price'
            ? 'price'
            : 'date';
    }
}
