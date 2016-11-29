<?php namespace Bedard\Shop\Api;

use Bedard\Shop\Classes\ApiController;
use Bedard\Shop\Repositories\CartRepository;
use Exception;
use Log;

class Cart extends ApiController
{
    /**
     * Add an inventory to the cart.
     *
     * @param CartRepository $repository
     */
    public function add(CartRepository $repository)
    {
        try {
            // @todo
        } catch (Exception $e) {
            Log::error($e->getMessage());

            abort(500, $e->getMessage());
        }
    }

    /**
     * Show the current cart.
     *
     * @param  \Bedard\Shop\Repositories\CartRepository     $repository
     * @return \Bedard\Shop\Models\Cart
     */
    public function index(CartRepository $repository)
    {
        try {
            return $repository->current();
        } catch (Exception $e) {
            Log::error($e->getMessage());

            abort(500, $e->getMessage());
        }
    }

    /**
     * Create a new cart.
     *
     * @param  \Bedard\Shop\Repositories\CartRepository     $repository
     * @return \Bedard\Shop\Models\Cart
     */
    public function store(CartRepository $repository)
    {
        try {

            // @todo
        } catch (Exception $e) {
            Log::error($e->getMessage());

            abort(500, $e->getMessage());
        }
    }
}
