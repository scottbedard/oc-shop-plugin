<?php namespace Bedard\Shop\Repositories;

use Bedard\Shop\Models\Cart;
use Bedard\Shop\Models\CartItem;
use Bedard\Shop\Models\CartSettings;
use Bedard\Shop\Models\Inventory;
use Cookie;
use Exception;
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
    public function addInventory($inventoryId, $quantity)
    {
        $inventory = Inventory::findOrFail($inventoryId);

        $cart = $this->getCart();
        $item = CartItem::firstOrCreate([
            'cart_id' => $cart->id,
            'inventory_id' => $inventory->id,
        ]);

        $item->quantity += $quantity;
        if ($item->quantity > $inventory->quantity) {
            $item->quantity = $inventory->quantity;
        }

        $item->save();

        $item->load([
            'inventory.optionValues.option',
            'inventory.product' => function ($product) {
                return $product->joinPrice()->with('thumbnails');
            },
        ]);

        return $item;
    }

    /**
     * Apply a promotion to the cart.
     *
     * @param  string                   $code
     * @return \Bedard\Shop\Models\Cart
     */
    public function applyPromotion($code)
    {
        $cart = $this->getCart();

        $cart->applyPromotion($code);

        return $this->loadCart();
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
    public function deleteItem($inventoryId)
    {
        $cart = $this->getCart();

        $item = $cart->items()->whereInventoryId($inventoryId)->first();

        if ($item) {
            return $item->delete();
        }

        return false;
    }

    /**
     * Determine if a cart exists.
     *
     * @return bool
     */
    public function exists()
    {
        $token = $this->getToken();

        return Cart::whereToken($token)->isOpen()->exists();
    }

    /**
     * Get the current cart, or create one if none exists.
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @return \Bedard\Shop\Models\Cart
     */
    public function getCart()
    {
        if ($this->cart !== null) {
            return $this->cart;
        }

        $token = $this->getToken();

        if (! $token) {
            return $this->create();
        }

        try {
            $this->cart = Cart::whereToken($token)
                ->isOpen()
                ->firstOrFail();
        } catch (Exception $e) {
            $this->cart = $this->create();
        }

        return $this->cart;
    }

    /**
     * Get the cart token.
     *
     * @return string
     */
    public function getToken()
    {
        $token = Session::get(self::CART_KEY);
        if (! $token && Cookie::has(self::CART_KEY)) {
            $token = Cookie::get(self::CART_KEY);
        }

        return $token;
    }

    /**
     * Load the related cart data.
     *
     * @return \Bedard\Shop\Models\Cart
     */
    public function loadCart()
    {
        $this->getCart()->load([
            'items.inventory.product' => function ($product) {
                $product->joinPrice()->with('thumbnails');
            },
            'items.inventory.optionValues.option',
            'promotion',
        ]);

        return $this->cart;
    }

    /**
     * Set an item's quantity in the current cart.
     *
     * @param  int
     * @param  int
     * @return \Bedard\Shop\Models\Cart
     */
    public function setInventory($inventoryId, $quantity)
    {
        $inventory = Inventory::findOrFail($inventoryId);

        if ($quantity <= 0) {
            return $this->deleteItem($inventory->id);
        }

        $cart = $this->getCart();
        $item = CartItem::firstOrCreate([
            'cart_id' => $cart->id,
            'inventory_id' => $inventory->id,
        ]);

        $item->quantity = $quantity;

        return $item->save();
    }

    /**
     * Update multiple inventories.
     *
     * @param  array  $inventories
     * @return void
     */
    public function updateInventories(array $inventories)
    {
        foreach ($inventories as $inventoryId => $quantity) {
            $this->setInventory($inventoryId, $quantity);
        }
    }

    /**
     * Update a single item quantity.
     *
     * @param  int                          $itemId
     * @param  int                          $quantity
     * @return \Bedard\Shop\Models\CartItem
     */
    public function updateItem($itemId, $quantity)
    {
        $cart = $this->getCart();

        $item = $cart->items()->find($itemId);

        if ($item) {
            $item->quantity = $quantity;
            $item->save();
        }

        return $item;
    }
}
