<?php namespace Bedard\Shop\Models;

use Model;

/**
 * Status Model.
 */
class Status extends Model
{
    use \October\Rain\Database\Traits\SoftDelete;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_shop_statuses';

    /**
     * @var array Attribute casting
     */
    protected $casts = [
        'is_default' => 'boolean',
    ];

    /**
     * @var array Dates
     */
    protected $dates = [
        'deleted_at',
    ];

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'color',
        'icon',
        'is_default',
        'name',
    ];

    /**
     * @var array Relations
     */
    public $belongsToMany = [
        'carts' => [
            'Bedard\Shop\Models\Cart',
            'pivot' => ['created_at'],
            'table' => 'bedard_shop_cart_status',
        ],
    ];

    /**
     * After save.
     *
     * @return void
     */
    public function afterSave()
    {
        $this->cleanDuplicateDefaults();
    }

    /**
     * Before delete.
     *
     * @return void
     */
    public function beforeDelete()
    {
        // prevent the deletion of the default status
        if ($this->is_default) {
            return false;
        }
    }

    /**
     * Prevent multiple statuses from having a default status.
     *
     * @return void
     */
    protected function cleanDuplicateDefaults()
    {
        if ($this->is_default) {
            self::isDefault()
                ->where('id', '<>', $this->id)
                ->update(['is_default' => false]);
        }
    }

    /**
     * Select the default status.
     *
     * @param  \October\Rain\Database\Builder $query
     * @return \October\Rain\Database\Builder
     */
    public function scopeIsDefault($query)
    {
        return $query->where('is_default', 1);
    }
}
