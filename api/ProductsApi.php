<?php namespace Bedard\Shop\Api;

use Bedard\Shop\Classes\ApiController;
use Bedard\Shop\Models\ApiSettings;
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
        $options = ApiSettings::getProductsOptions();

        if (! $options['is_enabled']) {
            return abort(403, 'Forbidden');
        }

        return $repository->get($query, $options);
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
        $options = ApiSettings::getProductOptions();

        if (! $options['is_enabled']) {
            return abort(403, 'Forbidden');
        }

        return $repository->find($slug, $query);
    }
}
