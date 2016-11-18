<?php namespace Bedard\Shop\Repositories;

use Bedard\Shop\Models\Product;

class ProductRepository
{
    /**
     * Find a product.
     *
     * @param  string   $slug
     * @param  array    $params
     * @return \Bedard\Shop\Models\Product
     */
    public function find($slug, array $params = [])
    {
        $product = Product::isEnabled()
            ->joinPrice()
            ->with([
                'images',
                'inventories.optionValues',
                'options.values',
            ])
            ->whereSlug($slug);

        return $product->firstOrFail();
    }
}
