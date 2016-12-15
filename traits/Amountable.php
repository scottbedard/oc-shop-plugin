<?php namespace Bedard\Shop\Traits;

trait Amountable
{
    /**
     * Boot the amountable trait for this model.
     *
     * @return void
     */
    public static function bootAmountable()
    {
        static::extend(function ($model) {
            $model->rules = array_merge($model->rules, [
                'amount_exact' => 'numeric|min:0',
                'amount_percentage' => 'numeric|min:0|max:100',
            ]);

            $model->purgeable = array_merge($model->purgeable, [
                'amount_exact',
                'amount_percentage',
            ]);

            $model->attributes = array_merge($model->attributes, [
                'amount_exact' => 0,
                'amount_percentage' => 0,
            ]);

            $model->addFillable('amount_exact', 'amount_percentage');

            $model->bindEvent('model.beforeSave', function () use ($model) {
                $model->setAmount();
            });
        });
    }

    /**
     * Get the exact amount.
     *
     * @return float
     */
    public function getAmountExactAttribute()
    {
        return ! $this->is_percentage ? $this->amount : 0;
    }

    /**
     * Get the percentage amount.
     *
     * @return float
     */
    public function getAmountPercentageAttribute()
    {
        return $this->is_percentage ? $this->amount : 0;
    }

    /**
     * Set the promotion amount.
     *
     * @return  void
     */
    public function setAmount()
    {
        $exact = $this->getOriginalPurgeValue('amount_exact');
        $percentage = $this->getOriginalPurgeValue('amount_percentage');

        $this->amount = $this->is_percentage
            ? $percentage
            : $exact;
    }
}
