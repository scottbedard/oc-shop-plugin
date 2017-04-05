<?php namespace Bedard\Shop\Classes;

class Repository
{
    /**
     * Order the results.
     *
     * @param  \October\Rain\Database\Builder   $query
     * @param  array                            $options
     * @return void
     */
    protected function orderResults(&$query, array $options)
    {
        if (
            array_key_exists('order', $options) &&
            is_string($options['order'])
        ) {
            $orderParam = array_map('trim', explode(',', $options['order']));
            $column = $orderParam[0];
            $direction = count($orderParam) > 1 ? $orderParam[1] : 'asc';

            $query->orderBy($column, $direction);
        }
    }

    /**
     * Paginate the results.
     *
     * @param  \October\Rain\Database\Builder   $query
     * @param  array                            $options
     * @param  integer                          $total
     * @return void
     */
    protected function paginateResults(&$query, array $options, $total)
    {
        if (array_key_exists('skip', $options)) {
            $query->skip((int) $options['skip']);
        }

        if (array_key_exists('take', $options)) {
            $query->take((int) $options['take']);
        } else {
            $query->take($total);
        }
    }

    /**
     * Select specific columns.
     *
     * @param  \October\Rain\Database\Builder   $query
     * @param  array                            $options
     * @return void
     */
    protected function selectColumns(&$query, array $options)
    {
        if (
            array_key_exists('columns', $options) &&
            is_array($options['columns'])
        ) {
            $query->addSelect($options['columns']);
        }
    }

    /**
     * Eager load related models.
     *
     * @param  \October\Rain\Database\Builder   $query
     * @param  array                            $options
     * @return void
     */
    protected function withRelationships(&$query, array $options)
    {
        if (
            array_key_exists('relationships', $options) &&
            is_array($options['relationships'])
        ) {
            foreach ($options['relationships'] as $relationship) {
                $query->with([
                    $relationship => function ($model) {
                        // @todo: add controls to select columns of a relationship
                        $model->select('*');
                    },
                ]);
            }
        }
    }
}
