<?php namespace Bedard\Shop\Repositories;

use Bedard\Shop\Classes\Repository;
use Bedard\Shop\Models\Cart;
use Bedard\Shop\Models\CartItem;
use Bedard\Shop\Models\Inventory;
use Session;

class CartRepository extends Repository
{
    /**
     * @var string  Cart persistence key.
     */
    const CART_KEY = 'bedard_shop_cart';

    /**
     * @var \Bedard\Shop\Models\Cart|null
     */
    protected $cart = null;

    /**
     * Add an inventory to the cart.
     *
     * @param int $inventoryId
     * @param int $quantity
     */
    public function add($inventoryId, $quantity)
    {
        $cart = $this->findOrCreate();

        return $cart->addInventory($inventoryId, $quantity);
    }

    /**
     * Create a new cart.
     *
     * @return \Bedard\Shop\Models\Cart
     */
    public function create()
    {
        $cart = Cart::create();

        Session::put(self::CART_KEY, [
            'id' => $cart->id,
            'token' => $cart->token,
        ]);

        return $cart;
    }

    /**
     * Find the current cart.
     *
     * @param  array                    $options
     * @return \Bedard\Shop\Models\Cart|null
     */
    public function find($options = [])
    {
        if ($this->cart) {
            return $this->cart;
        }

        $token = $this->getToken();

        if ($token) {
            $query = Cart::isOpen()->whereToken($token['token']);
            $this->selectColumns($query, $options);
            $this->withRelationships($query, $options);

            if ($cart = $query->find($token['id'])) {
                return $cart;
            }
        }

        return null;
    }

    /**
     * Find the current cart, or create a new one.
     *
     * @param  array                    $options
     * @return \Bedard\Shop\Models\Cart
     */
    public function findOrCreate($options = [])
    {
        return $this->find($options) ?: $this->create();
    }

    /**
     * Find the current cart or instantiate a new one.
     *
     * @param  array                    $options
     * @return \Bedard\Shop\Models\Cart
     */
    public function findOrNew($options = [])
    {
        return $this->find($options) ?: new Cart;
    }

    /**
     * Get the cart token.
     *
     * @return string
     */
    public function getToken()
    {
        $token = Session::get(self::CART_KEY);

        return $token;
    }

    /**
     * Remove an item from the cart.
     *
     * @param  int                      $itemId
     * @return \Bedard\Shop\Models\CartItem
     */
    public function remove($itemId)
    {
        $cart = $this->findOrCreate();

        return $cart->removeItem($itemId);
    }

    /**
     * Touch the current cart.
     *
     * @return void
     */
    public function touch()
    {
        $cart = $this->findOrCreate();

        $cart->touch();

        return $cart;
    }

    /**
     * Add or remove quantity to an existing CartItem.
     *
     * @param  int                          $inventoryId
     * @param  int                          $quantity
     * @return \Bedard\Shop\Models\CartItem
     */
    public function update($inventoryId, $quantity)
    {
        $cart = $this->findOrCreate();

        return $cart->setInventory($inventoryId, $quantity);
    }
}
