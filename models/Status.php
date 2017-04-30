<?php namespace Bedard\Shop\Models;

use Flash;
use Lang;
use Model;
use October\Rain\Database\ModelException;

/**
 * Status Model.
 */
class Status extends Model
{
    use \October\Rain\Database\Traits\SoftDelete,
        \October\Rain\Database\Traits\Validation;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_shop_statuses';

    /**
     * @var array Attribute casting
     */
    protected $casts = [
        'is_abandoned' => 'boolean',
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
        'is_abandoned',
        'is_default',
        'name',
    ];

    /**
     * @var array Relations
     */
    public $belongsToMany = [
        'carts' => [
            'Bedard\Shop\Models\Cart',
            'pivot' => [
                'created_at',
                'driver',
            ],
            'table' => 'bedard_shop_cart_status',
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
        $this->preventDuplicateAbandoned();
        $this->preventDuplicateDefaults();
    }

    /**
     * Before delete.
     *
     * @return void
     */
    public function beforeDelete()
    {
        // prevent the deletion of the default or abandoned statuses
        if ($this->is_abandoned || $this->is_default) {
            return false;
        }
    }

    /**
     * Before save.
     *
     * @return void
     */
    public function beforeSave()
    {
        $this->preventDefaultRemoval();
        $this->validateFlags();
    }

    /**
     * Determine if the status can be deleted.
     *
     * @return boolean
     */
    public function isDeleteable()
    {
        return ! $this->is_default && ! $this->is_abandoned;
    }

    /**
     * Prevent the default status from being removed.
     *
     * @throws \October\Rain\Database\ModelException
     * @return void
     */
    protected function preventDefaultRemoval()
    {
        if ($this->getOriginal('is_default') && ! $this->is_default) {
            $message = Lang::get('bedard.shop::lang.statuses.form.is_default_removed');

            Flash::error($message);
            throw new ModelException($this, $message);
        }
    }

    /**
     * Prevent multiple statuses from having an abandoned status.
     *
     * @return void
     */
    protected function preventDuplicateAbandoned()
    {
        if ($this->is_abandoned) {
            self::isAbandoned()
                ->where('id', '<>', $this->id)
                ->update(['is_abandoned' => false]);
        }
    }

    /**
     * Prevent multiple statuses from having a default status.
     *
     * @return void
     */
    protected function preventDuplicateDefaults()
    {
        if ($this->is_default) {
            self::isDefault()
                ->where('id', '<>', $this->id)
                ->update(['is_default' => false]);
        }
    }

    /**
     * Select the abandoned status.
     *
     * @param  \October\Rain\Database\Builder $query
     * @return \October\Rain\Database\Builder
     */
    public function scopeIsAbandoned($query)
    {
        return $query->where('is_abandoned', 1);
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

    /**
     * Validate status flags.
     *
     * @throws \Exception
     * @return void
     */
    protected function validateFlags()
    {
        if ($this->is_abandoned && $this->is_default) {
            $message = Lang::get('bedard.shop::lang.statuses.form.is_default_abandoned_exception');

            Flash::error($message);
            throw new ModelException($this, $message);
        }
    }
}
