<?php namespace Bedard\Shop\Api;

use Bedard\Shop\Classes\ApiController;
use Bedard\Shop\Models\ApiSettings;
use Bedard\Shop\Repositories\CartRepository;
use Exception;
use Log;

class Carts extends ApiController
{
    /**
     * Show the current cart.
     *
     * @param  \Bedard\Shop\Repositories\CartRepository     $repository
     * @return \Bedard\Shop\Models\Cart
     */
    public function show(CartRepository $repository)
    {
        try {

            // @todo

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
