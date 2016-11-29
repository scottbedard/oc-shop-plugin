<?php namespace Bedard\Shop\Repositories;

use Bedard\Shop\Models\Cart;
use Bedard\Shop\Models\CartSettings;
use Cookie;
use Session;

class CartRepository
{
    /**
     * @var string  Cart persistence key.
     */
    const CART_KEY = 'bedard_shop_cart';

    /**
     * Create a new cart.
     *
     * @return \Bedard\Shop\Models\Cart
     */
    public function create()
    {
        $cart = Cart::create();

        Session::put(self::CART_KEY, $cart->token);
        Cookie::queue(self::CART_KEY, $cart->token, CartSettings::getLifespan());

        return $cart;
    }

    /**
     * Get the current cart, or create one if none exists.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @return \Bedard\Shop\Models\Cart
     */
    public function find()
    {
        $token = Session::get(self::CART_KEY);

        if (! $token && Cookie::has(self::CART_KEY)) {
            $token = Cookie::get(self::CART_KEY);
        }

        if (! $token) {
            return $this->create();
        }

        return Cart::whereToken($token)->firstOrFail();
    }
}
