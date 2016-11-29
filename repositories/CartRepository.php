<?php namespace Bedard\Shop\Repositories;


class CartRepository
{
    /**
     * @var string  Cart persistence key.
     */
    const CART_COOKIE = 'bedard_shop_cart';

    /**
     * Get the current repository, or create one if none exists.
     *
     * @return [type] [description]
     */
    public function current()
    {
        return 'foo';
    }
}
