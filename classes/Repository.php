<?php namespace Bedard\Shop\Classes;

class Repository
{
    /**
     * Eager load related models.
     *
     * @param  \October\Rain\Database\Builder   $query
     * @param  array                            $options
     * @return \October\Rain\Database\Builder
     */
    protected function queryWith($query, $options = [])
    {
        if (array_key_exists('with', $options)) {
            $query->with(explode(',', $options['with']));
        }

        return $query;
    }
}
