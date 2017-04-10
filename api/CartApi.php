<?php namespace Bedard\Shop\Api;

use Bedard\Shop\Classes\ApiController;
use Bedard\Shop\Models\ApiSettings;
use Bedard\Shop\Repositories\CartRepository;

class CartApi extends ApiController
{
    /**
     * Add an item to the cart.
     *
     * @param  CartRepository               $repository
     * @return \Bedard\Shop\Models\CartItem
     */
    public function add(CartRepository $repository)
    {
        $inventoryId = (int) input('inventoryId');
        $quantity = (int) input('quantity') ?: 1;

        return $repository->add($inventoryId, $quantity);
    }

    /**
     * Find the current cart.
     *
     * @param  CartRepository           $repository
     * @return \Bedard\Shop\Models\Cart
     */
    public function index(CartRepository $repository)
    {
        $options = ApiSettings::getCartOptions();

        if (! array_key_exists('is_enabled', $options) || ! $options['is_enabled']) {
            return abort(403, 'Forbidden');
        }

        return $repository->findOrNew($options);
    }

    /**
     * Add or remove quantity to an existing CartItem.
     *
     * @param  CartRepository               $repository
     * @return \Bedard\Shop\Models\CartItem
     */
    public function update(CartRepository $repository)
    {
        $inventoryId = (int) input('inventoryId');
        $quantity = (int) input('quantity') ?: 1;

        return $repository->update($inventoryId, $quantity);
    }
}
