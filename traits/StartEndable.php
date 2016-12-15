<?php namespace Bedard\Shop\Traits;

use Flash;
use Lang;
use October\Rain\Database\ModelException;

trait StartEndable
{
    /**
     * Boot the startendable trait for this model.
     *
     * @return void
     */
    public static function bootStartEndable()
    {
        static::extend(function($model) {
            $model->bindEvent('model.afterValidate', function() use ($model) {
                $model->validateStartEndDates();
            });
        });
    }

    /**
     * Ensure the start and end dates are valid.
     *
     * @return void
     */
    public function validateStartEndDates()
    {
        // Start date must be after the end date
        if ($this->start_at !== null &&
            $this->end_at !== null &&
            $this->start_at >= $this->end_at) {
            Flash::error(Lang::get('bedard.shop::lang.traits.startendable.start_at_invalid'));
            throw new ModelException($this);
        }
    }
}
