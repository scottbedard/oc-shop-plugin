<?php namespace Bedard\Shop\Api;

use Bedard\Shop\Classes\ApiController;
use Bedard\Shop\Models\ApiSettings;
use Bedard\Shop\Repositories\ProductRepository;
use Exception;
use Log;

class Products extends ApiController
{

    /**
     * Find a single product.
     *
     * @param  \Bedard\Shop\Repositories\ProductRepository  $repository
     * @param  string                                       $slug
     * @return \Bedard\Shop\Models\Product
     */
    public function show(ProductRepository $repository, $slug)
    {
        try {
            $params = [

            ];

            return $repository->find($slug, $params);
        } catch (Exception $e) {
            Log::error($e->getMessage());

            abort(500, $e->getMessage());
        }
    }
}
