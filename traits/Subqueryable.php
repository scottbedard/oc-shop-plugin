<?php namespace Bedard\Shop\Traits;

use DB;
use October\Rain\Database\Builder;

trait Subqueryable
{
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

        return $subquery instanceof Builder
            ? $query->selectSub($subquery->getQuery(), $as)
            : $query->selectSub($subquery, $as);
    }

    /**
     * Join the query with a subquery. Warning, in order to use this method
     * properly, the join must be executed at the start of the query. If
     * it's added t the end of the query, the bindings won't match up.
     *
     * @param  \October\Rain\Database\Builder   $query      Query being joined to
     * @param  \October\Rain\Database\Builder   $subquery   Subquery being joined
     * @param  string                           $alias      Joined table alias
     * @param  string                           $left       Left side of condition
     * @param  string                           $operator   Join condition operator
     * @param  string                           $right      Right side of condition
     * @param  string                           $join       Join type [ join, leftJoin ]
     * @return \October\Rain\Database\Builder
     */
    public function scopeJoinSubquery($query, $subquery, $alias, $left, $operator, $right, $join = 'join')
    {
        $self = $this->getTable().'.*';
        if (! in_array($self, $query->getQuery()->columns)) {
            $query->addSelect($self);
        }

        $subquery = $subquery->getQuery();
        $raw = DB::raw('('.$subquery->toSql().') '.$alias);

        return $query->$join($raw, $left, $operator, $right)->mergeBindings($subquery);
    }
}
