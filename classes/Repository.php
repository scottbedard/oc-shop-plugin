<?php namespace Bedard\Shop\Classes;

class Repository
{
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
