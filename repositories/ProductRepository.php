<?php namespace Bedard\Shop\Repositories;

use Bedard\Shop\Classes\Repository;
use Bedard\Shop\Models\Product;

class ProductRepository extends Repository
{
    /**
     * Fetch products.
     *
     * @return October\Rain\Database\Collection
     */
    public function get($query)
    {
        $products = Product::get();

        return $products;
    }
}
