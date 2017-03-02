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
}
