<?php namespace Bedard\Shop\Repositories;

use Bedard\Shop\Classes\Repository;
use Bedard\Shop\Models\Cart;
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
     * @return \Bedard\Shop\Models\Cart|null
     */
    public function find()
    {
        if ($this->cart) {
            return $this->cart;
        }

        $token = $this->getToken();

        if ($token) {
            $cart = Cart::whereToken($token['token'])->find($token['id']);

            if ($cart) {
                return $cart;
            }
        }

        return null;
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

    public function findOrNew()
    {
        return $this->find() ?: new Cart;
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
}
