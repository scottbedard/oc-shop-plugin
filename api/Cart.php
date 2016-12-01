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
            $inventoryId = input('inventoryId');
            $quantity = input('quantity');

            return $repository->addInventory($inventoryId, $quantity);
        } catch (Exception $e) {
            Log::error($e->getMessage());

            abort(500, $e->getMessage());
        }
    }

    /**
     * Determine if a cart exists or not.
     *
     * @param  CartRepository $repository
     * @return bool
     */
    public function exists(CartRepository $repository)
    {
        try {
            return $repository->exists() ? 'true' : 'false';
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
            return $repository->loadCart();
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
