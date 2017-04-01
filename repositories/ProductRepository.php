<?php namespace Bedard\Shop\Repositories;

use Bedard\Shop\Classes\Repository;
use Bedard\Shop\Models\Product;

class ProductRepository extends Repository
{
    /**
     * Find a product.
     *
     * @param  string                       $slug
     * @param  array                        $options
     * @return Bedard\Shop\Models\Product
     */
    public function find($slug, array $options = [])
    {
        $query = Product::whereSlug($slug);

        // eager load related models
        $query = $this->queryWith($query, $options);

        return $query->firstOrFail();
    }

    /**
     * Fetch products.
     *
     * @param  array                            $options
     * @return October\Rain\Database\Collection
     */
    public function get(array $options = [])
    {
        $query = (new Product)->newQuery();

        // apply categories scope
        if (array_key_exists('categories', $options)) {
            $query->inCategories($options['categories']);
        }

        // eager load related models
        $query = $this->queryWith($query, $options);

        return $query->get();
    }
}
