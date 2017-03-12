<?php namespace Bedard\Shop\Repositories;

use Bedard\Shop\Classes\Repository;
use Bedard\Shop\Models\Product;

class ProductRepository extends Repository
{
    /**
     * Fetch products.
     *
     * @param  array                            $options
     * @return October\Rain\Database\Collection
     */
    public function get(array $options = [])
    {
        $products = (new Product)->newQuery();

        // eager load related models
        $query = $this->queryWith($query, $options);

        return $products;
    }
}
