<?php namespace Bedard\Shop\Traits;

use DB;
use Carbon\Carbon;
use October\Rain\Database\Builder;

trait Timeable
{
    /**
     * Boot the timeable trait for a model.
     *
     * @return void
     */
    public static function bootTimeable()
    {
        static::extend(function ($model) {
            $model->addFillable('start_at', 'end_at');
            $model->addDateAttribute('start_at');
            $model->addDateAttribute('end_at');
        });
    }

    /**
     * Get the raw date string.
     *
     * @return \Illuminate\Database\Query\Expression
     */
    protected function getRawCarbonString()
    {
        return DB::raw("'".(string) Carbon::now()."'");
    }

    /**
     * Query models that are active.
     *
     * @param  \October\Rain\Database\Builder   $query
     * @return \October\Rain\Database\Builder
     */
    public function scopeIsActive(Builder $query)
    {
        return $query->where(function ($model) {
            return $model->isNotExpired()->isNotUpcoming();
        });
    }

    /**
     * Query models that are expired.
     *
     * @param  \October\Rain\Database\Builder   $query
     * @return \October\Rain\Database\Builder
     */
    public function scopeIsExpired(Builder $query)
    {
        return $query->where(function ($model) {
            return $model->whereNotNull('end_at')
                ->where('end_at', '<=', $this->getRawCarbonString());
        });
    }

    /**
     * Query models that are upcoming.
     *
     * @param  \October\Rain\Database\Builder   $query
     * @return \October\Rain\Database\Builder
     */
    public function scopeIsUpcoming(Builder $query)
    {
        return $query->where(function ($model) {
            return $model->whereNotNull('start_at')
                ->where('start_at', '>', $this->getRawCarbonString());
        });
    }

    /**
     * Query models that are not active.
     *
     * @param  \October\Rain\Database\Builder   $query
     * @return \October\Rain\Database\Builder
     */
    public function scopeIsNotActive(Builder $query)
    {
        return $query->where(function ($model) {
            return $model->isExpired()->orWhere(function ($q) {
                $q->isUpcoming();
            });
        });
    }

    /**
     * Query models that are not expired.
     *
     * @param  \October\Rain\Database\Builder   $query
     * @return \October\Rain\Database\Builder
     */
    public function scopeIsNotExpired(Builder $query)
    {
        return $query->where(function ($model) {
            return $model->whereNull('end_at')
                ->orWhere('end_at', '>', $this->getRawCarbonString());
        });
    }

    /**
     * Query models that are not upcoming.
     *
     * @param  \October\Rain\Database\Builder   $query
     * @return \October\Rain\Database\Builder
     */
    public function scopeIsNotUpcoming(Builder $query)
    {
        return $query->where(function ($model) {
            return $model->whereNull('start_at')
                ->orWhere('start_at', '<=', $this->getRawCarbonString());
        });
    }
}
