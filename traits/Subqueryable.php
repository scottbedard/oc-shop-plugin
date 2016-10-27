<?php namespace Bedard\Shop\Traits;

use DB;
use October\Rain\Database\Builder;

trait Subqueryable
{
    /**
     * Get the table associated with the model.
     *
     * @return string
     */
    abstract public function getTable();

    /**
     * Select from a subquery.
     *
     * @param  \October\Rain\Database\Builder
     * @return \October\Rain\Database\Builder
     */
    public function scopeSelectSubquery($query, $subquery, $as)
    {
        if (empty($query->getQuery()->columns)) {
            $query->select($this->getTable().'.*');
        }

        $q = $subquery instanceof Builder ? $subquery->getQuery() : $subquery;

        return $query->selectSub($q, $as);
    }
}
