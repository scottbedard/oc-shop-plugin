<?php namespace Bedard\Shop\Repositories;

use Bedard\Shop\Models\Cart;
use Bedard\Shop\Models\CartItem;
use Bedard\Shop\Models\CartSettings;
use Bedard\Shop\Models\Inventory;
use Cookie;
use Session;

class CartRepository
{
    /**
     * @var string  Cart persistence key.
     */
    const CART_KEY = 'bedard_shop_cart';

    /**
     * @var \Bedard\Shop\Models\Cart
     */
    protected $cart = null;

    /**
     * Add an item to the curent cart.
     *
     * @param  int
     * @param  int
     * @return \Bedard\Shop\Models\Cart
     */
    public function add($inventoryId, $quantity)
    {
        $inventory = Inventory::findOrFail($inventoryId);

        $cart = $this->find();
        $item = CartItem::firstOrCreate([
            'cart_id' => $cart->id,
            'inventory_id' => $inventory->id,
        ]);

        $item->quantity += $quantity;
        if ($item->quantity > $inventory->quantity) {
            $item->quantity = $inventory->quantity;
        }

        $item->save();

        return $cart;
    }

    /**
     * Create a new cart.
     *
     * @return \Bedard\Shop\Models\Cart
     */
    public function create()
    {
        $this->cart = Cart::create();

        Session::put(self::CART_KEY, $this->cart->token);
        Cookie::queue(self::CART_KEY, $this->cart->token, CartSettings::getLifespan());

        return $this->cart;
    }

    /**
     * Delete an inventory from the cart.
     *
     * @param  int
     * @return \Bedard\Shop\Models\Cart
     */
    public function delete($inventoryId)
    {
        $cart = $this->find();

        $item = $cart->items()->whereInventoryId($inventoryId)->first();

        if ($item) {
            $item->delete();
        }

        $cart->load('items');

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
        if ($this->cart !== null) {
            return $this->cart;
        }

        $token = Session::get(self::CART_KEY);
        if (! $token && Cookie::has(self::CART_KEY)) {
            $token = Cookie::get(self::CART_KEY);
        }

        if (! $token) {
            return $this->create();
        }

        return Cart::whereToken($token)
            ->isOpen()
            ->with('items')
            ->firstOrFail();
    }

    /**
     * Set an item's quantity in the curent cart.
     *
     * @param  int
     * @param  int
     * @return \Bedard\Shop\Models\Cart
     */
    public function set($inventoryId, $quantity)
    {
        $inventory = Inventory::findOrFail($inventoryId);

        if ($quantity <= 0) {
            return $this->delete($inventory->id);
        }

        $cart = $this->find();
        $item = CartItem::firstOrCreate([
            'cart_id' => $cart->id,
            'inventory_id' => $inventory->id,
        ]);

        $item->quantity = $quantity;
        if ($item->quantity > $inventory->quantity) {
            $item->quantity = $inventory->quantity;
        }

        $item->save();

        return $cart;
    }
}
