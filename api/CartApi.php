<?php namespace Bedard\Shop\Api;

use Bedard\Shop\Classes\ApiController;

class CartApi extends ApiController
{
    /**
     * Add an item to the cart.
     *
     * @param  CartRepository           $repository
     * @param  string                   $slug
     * @param  integer                  $quantity
     * @return \Bedard\Shop\Models\Cart
     */
    public function add(CartRepository $repository, $slug, $quantity = 1)
    {
        return $repository->add($slug, $quantity);
    }

    /**
     * Find the current cart.
     *
     * @param  CartRepository           $repository
     * @return \Bedard\Shop\Models\Cart
     */
    public function index(CartRepository $repository)
    {
        return $repository->findOrNew();
    }
}
