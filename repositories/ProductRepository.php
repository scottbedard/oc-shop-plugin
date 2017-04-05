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
        $query = (new Product)->newQuery();
        // $this->orderResults($query, $params);
        $this->selectColumns($query, $options);
        $this->withRelationships($query, $options);

        // select products in a given set of categories
        if (array_key_exists('categories', $params)) {
            $query->inCategories($params['categories']);
        }

        return $query->get();
    }
}
