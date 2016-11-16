<?php namespace Bedard\Shop\Classes;

use Bedard\Shop\Models\Category;
use Bedard\Shop\Models\Product;

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
     * @var \October\Rain\Databse\Builder   Products query.
     */
    public $query;

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

        $this->buildQuery();
    }

    /**
     * Build up the products query.
     *
     * @return void
     */
    protected function buildQuery()
    {
        $this->query = Product::isEnabled();

        $this->applySelectStatements();
        $this->applyWhereStatements();
        $this->applyOrderByStatements();
    }

    /**
     * Apply a custom sort order to a products query.
     *
     * @return void
     */
    protected function applyCustomOrder()
    {
        $order = '';
        foreach ($this->category->product_order as $index => $id) {
            $order .= "when {$id} then {$index} ";
        }

        $this->query->orderByRaw("case id {$order} else 'last' end");
    }

    /**
     * Apply an order by statement to the products query.
     *
     * @return void
     */
    protected function applyOrderByStatements()
    {
        if (array_key_exists('products_sort_column', $this->params) &&
            array_key_exists('products_sort_direction', $this->params)) {
            $this->query->orderBy($this->params['products_sort_column'], $this->params['products_sort_direction']);
        } elseif ($this->category->isCustomSorted()) {
            $this->applyCustomOrder();
        } else {
            $this->query->orderBy($this->category->product_sort_column, $this->category->product_sort_direction);
        }
    }

    /**
     * Apply filters to a products query.
     *
     * @return void
     */
    protected function applyProductFilters()
    {
        $this->query->where(function ($q) {
            foreach ($this->category->filters as $filter) {
                $right = $filter->getRightClause();
                $q->where($filter->left, $filter->comparator, $right);
            }
        });
    }

    /**
     * Apply select statements and join prices.
     *
     * @return void
     */
    protected function applySelectStatements()
    {
        // apply select statements if neccessary
        if (array_key_exists('products_select', $this->params) && $this->params['products_select']) {
            $this->query->select($this->params['products_select']);

            // join the prices if neccessary
            if (in_array('price', $this->params['products_select'])) {
                $this->query->joinPrice();
            }
        }
    }

    /**
     * Restrict our query to products that should be visible in our category.
     *
     * @return void
     */
    protected function applyWhereStatements()
    {
        // if the category is filtered, grab our products from those
        if ($this->category->isFiltered()) {
            $this->applyProductFilters();
        }

        // otherwise grab all products appearing in our category
        else {
            $this->query->appearingInCategory($this->category->id);
        }
    }
}
