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
        $this->query = Product::isEnabled()->joinPrice();

        $this->applyWhereStatements();
        $this->applyPagination();
        $this->applyOrderByStatements();
        $this->loadRelatedModels();
    }

    /**
     * Apply a custom sort order to a products query.
     *
     * @return void
     */
    protected function applyCustomOrder()
    {
        $this->query
            ->join('bedard_shop_category_product', function ($join) {
                $join->on('bedard_shop_products.id', '=', 'bedard_shop_category_product.product_id')
                    ->where('bedard_shop_category_product.category_id', '=', $this->category->id);
            })
            ->orderBy('bedard_shop_category_product.sort_order');
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
     * Paginate the results.
     *
     * @return void
     */
    protected function applyPagination()
    {
        if (array_key_exists('page', $this->params) && $this->category->isPaginated()) {
            $resultsPerPage = $this->category->resultsPerPage();
            $resultsToSkip = ($this->params['page'] - 1) * $resultsPerPage;

            $this->query
                ->offset($resultsToSkip)
                ->limit($resultsPerPage);
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

    /**
     * Load related models.
     *
     * @return void
     */
    protected function loadRelatedModels()
    {
        if (array_key_exists('load_thumbnails', $this->params) && $this->params['load_thumbnails']) {
            $this->query->with('thumbnails');
        }
    }
}
