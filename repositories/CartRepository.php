<?php namespace Bedard\Shop\Repositories;

use Bedard\Shop\Classes\Repository;
use Bedard\Shop\Models\Cart;

class CartRepository extends Repository
{
    /**
     * Add an inventory to the cart.
     *
     * @param integer $inventoryId
     * @param integer $quantity
     */
    public function add($inventoryId, $quantity)
    {
        $cart = $this->findOrCreate();
    }

    /**
     * Create a new cart.
     *
     * @return \Bedard\Shop\Models\Cart
     */
    public function create()
    {
        return Cart::create();
    }

    /**
     * Find the current cart.
     *
     * @return \Bedard\Shop\Models\Cart|null
     */
    public function find()
    {
        // @todo
    }

    /**
     * Find the current cart, or create a new one.
     *
     * @return \Bedard\Shop\Models\Cart
     */
    public function findOrCreate()
    {
        return $this->find() ?: $this->create();
    }
}
