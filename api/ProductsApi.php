<?php namespace Bedard\Shop\Api;

use Bedard\Shop\Classes\ApiController;
use Bedard\Shop\Repositories\ProductRepository;

class ProductsApi extends ApiController
{
    /**
     * List products.
     *
     * @return October\Rain\Database\Collection
     */
    public function index(ProductRepository $repository)
    {
        $query = input();

        return $repository->get($query);
    }

    /**
     * Find a product.
     *
     * @param  ProductRepository            $repository
     * @param  string                       $slug
     * @return \Bedard\Shop\Models\Product
     */
    public function show(ProductRepository $repository, $slug)
    {
        $query = input();

        return $repository->find($slug, $query);
    }
}
