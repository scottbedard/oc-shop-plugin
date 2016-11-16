<?php namespace Bedard\Shop\Classes;

use Bedard\Shop\Models\Category;
use Bedard\Shop\Models\Product;
use October\Rain\Database\Builder;

class ProductsQuery
{
    /**
     * @var \Bedard\Shop\Models\Category    The category to query products for.
     */
    protected $category;

    /**
     * @var array   Query parameters.
     */
    protected $params;

    /**
     * Construct.
     *
     * @param \Bedard\Shop\Models\Category  $category
     * @param array                         $params
     */
    public function __construct(Category $category, array $params = [])
    {
        $this->category = $category;
        $this->params = $params;
    }

    /**
     * Execute a products query.
     *
     * @return \October\Rain\Database\Collection
     */
    public function get()
    {
        $query = Product::isEnabled();
        $this->applySelectStatements($query);
        $this->applyWhereStatements($query);
        $this->applyOrderByStatements($query);

        return $query->get();
    }

    /**
     * Apply a custom sort order to a products query.
     *
     * @param  \October\Rain\Database\Builder   $products
     * @return void
     */
    protected function applyCustomOrder(Builder &$query)
    {
        $order = '';
        foreach ($this->category->product_order as $index => $id) {
            $order .= "when {$id} then {$index} ";
        }

        $query->orderByRaw("case id {$order} else 'last' end");
    }

    /**
     * Apply an order by statement to the products query.
     *
     * @param  \October\Rain\Database\Builder   $products
     * @return void
     */
    protected function applyOrderByStatements(Builder &$query)
    {
        if (array_key_exists('products_sort_column', $this->params) &&
            array_key_exists('products_sort_direction', $this->params)) {
            $query->orderBy($this->params['products_sort_column'], $this->params['products_sort_direction']);
        } elseif ($this->category->isCustomSorted()) {
            $this->applyCustomOrder($query);
        } else {
            $query->orderBy($this->category->product_sort_column, $this->category->product_sort_direction);
        }
    }

    /**
     * Apply filters to a products query.
     *
     * @param  \October\Rain\Database\Builder   $query
     * @return void
     */
    protected function applyProductFilters(Builder &$query)
    {
        $query->where(function ($q) {
            foreach ($this->category->filters as $filter) {
                $right = $filter->getRightClause();
                $q->where($filter->left, $filter->comparator, $right);
            }
        });
    }

    /**
     * Apply select statements and join prices.
     *
     * @param  \October\Rain\Database\Builder   $query
     * @return void
     */
    protected function applySelectStatements(Builder &$query)
    {
        // apply select statements if neccessary
        if (array_key_exists('products_select', $this->params) && $this->params['products_select']) {
            $query->select($this->params['products_select']);

            // join the prices if neccessary
            if (in_array('price', $this->params['products_select'])) {
                $query->joinPrice();
            }
        }
    }

    /**
     * Restrict our query to products that should be visible in our category.
     *
     * @param  \October\Rain\Database\Builder   $query
     * @return void
     */
    protected function applyWhereStatements(Builder &$query)
    {
        // if the category is filtered, grab our products from those
        if ($this->category->isFiltered()) {
            $this->applyProductFilters($query);
        }

        // otherwise grab all products appearing in our category
        else {
            $query->appearingInCategory($this->category->id);
        }
    }
}
