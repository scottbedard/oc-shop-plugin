<?php namespace Bedard\Shop\Repositories;

use Bedard\Shop\Classes\Repository;
use Bedard\Shop\Models\Product;

class ProductRepository extends Repository
{
    /**
     * Find a product.
     *
     * @param  string                       $slug
     * @param  array                        $params
     * @param  array                        $options
     * @return Bedard\Shop\Models\Product
     */
    public function find($slug, array $params = [], array $options = [])
    {
        $query = Product::whereSlug($slug);
        $this->selectColumns($query, $options);
        $this->withRelationships($query, $options);

        return $query->firstOrFail();
    }

    /**
     * Fetch products.
     *
     * @param  array                            $params
     * @param  array                            $options
     * @return October\Rain\Database\Collection
     */
    public function get(array $params = [], array $options = [])
    {
        $count = (new Product)->newQuery();
        $query = (new Product)->newQuery();
        $this->orderResults($query, $params);
        $this->selectColumns($query, $options);
        $this->withRelationships($query, $options);

        // select products in a given set of categories
        if (array_key_exists('categories', $params)) {
            $count->inCategories($params['categories']);
            $query->inCategories($params['categories']);
        }

        // count the results
        $total = $count->count();
        $this->paginateResults($query, $params, $total);

        return [
            'results' => $query->get(),
            'total' => $total,
        ];
    }
}
