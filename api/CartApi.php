<?php namespace Bedard\Shop\Api;

use Bedard\Shop\Classes\ApiController;

class CartApi extends ApiController
{
    /**
     * Add an item to the cart.
     *
     * @param CartRepository $repository
     */
    public function add(CartRepository $repository)
    {
    }

    public function index(CartRepository $repository)
    {
        return $repository->findOrNew();
    }
}
