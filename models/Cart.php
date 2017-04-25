<?php namespace Bedard\Shop\Models;

use Bedard\Shop\Classes\PaymentDriver;
use Carbon\Carbon;
use Exception;
use Model;
use Queue;

/**
 * Cart Model.
 */
class Cart extends Model
{
    /**
     * @var string The database table used by the model.
     */
    public $table = 'bedard_shop_carts';

    /**
     * @var array Default attributes
     */
    public $attributes = [
        'created_at' => null,
        'id' => null,
        'item_count' => 0,
        'item_total' => 0,
        'token' => null,
        'update_count' => 0,
        'updated_at' => null,
    ];

    /**
     * @var array Attribute casting
     */
    protected $casts = [
        'update_count' => 'integer',
        'item_count' => 'integer',
        'item_total' => 'float',
    ];

    /**
     * @var array Dates
     */
    protected $dates = [
        'closed_at',
    ];

    /**
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'closed_at',
        'closed_by',
        'item_count',
        'item_total',
        'update_count',
    ];

    /**
     * @var array Relations
     */
    public $belongsToMany = [
        'statuses' => [
            'Bedard\Shop\Models\Status',
            'order' => 'pivot_created_at desc',
            'pivot' => ['created_at'],
            'table' => 'bedard_shop_cart_status',
        ],
    ];

    public $hasMany = [
        'items' => [
            'Bedard\Shop\Models\CartItem',
        ],
    ];

    /**
     * Add inventory to the cart.
     *
     * @param  int $inventoryId
     * @param  int $quantity
     */
    public function addInventory($inventoryId, $quantity = 1)
    {
        $item = $this->getItemByInventoryId($inventoryId);

        $item->quantity += $quantity;

        return $this->saveItem($item);
    }

    /**
     * After create.
     *
     * @return void
     */
    public function afterCreate()
    {
        $this->createDefaultStatus();
    }

    /**
     * Before create.
     *
     * @return void
     */
    public function beforeCreate()
    {
        $this->generateToken();
    }

    /**
     * Before save.
     *
     * @return void
     */
    public function beforeSave()
    {
        $this->incrementUpdateCount();
    }

    /**
     * Create the default status.
     *
     * @return void
     */
    protected function createDefaultStatus()
    {
        $status = Status::isDefault()->first();

        if ($status) {
            $this->statuses()->attach($status);
        }
    }

    /**
     * Finalize a cart and reduce the available inventory.
     *
     * @param  PaymentDriver $driver
     * @return void
     */
    public function finalize(PaymentDriver $driver)
    {
        // throw an error if the cart is already closed
        if ($this->closed_at || $this->closed_by) {
            throw new Exception('Failed to close cart, it was already closed by ' . $this->closed_by);
        }

        // close the cart
        $this->closed_at = Carbon::now();
        $this->closed_by = get_class($driver);
        $this->save();

        // reduce our inventories
        $this->reduceInventories();
    }

    /**
     * Generate a random token.
     *
     * @return void
     */
    protected function generateToken()
    {
        $this->token = str_random(40);
    }

    /**
     * Get or instantiate an inventory.
     *
     * @param  int $inventoryId
     * @return \Bedard\Shop\Models\Inventory
     */
    public function getItemByInventoryId($inventoryId)
    {
        $inventory = Inventory::findOrFail($inventoryId);

        $item = $this->items()->firstOrNew([
            'cart_id' => $this->id,
            'inventory_id' => $inventoryId,
            'product_id' => $inventory->product_id,
        ]);

        $item->bindEvent('model.beforeSave', function () use ($inventory, $item) {
            if ($item->quantity > $inventory->quantity) {
                $item->quantity = $inventory->quantity;
            }
        });

        return $item;
    }

    /**
     * Get the most recent status.
     *
     * @return \Bedard\Shop\Models\Status
     */
    public function getStatusAttribute()
    {
        return $this->statuses->last();
    }

    /**
     * Increment the update count.
     *
     * @return void
     */
    protected function incrementUpdateCount()
    {
        $this->update_count++;
    }

    /**
     * Reduce the available inventory.
     *
     * @return void
     */
    public function reduceInventories()
    {
        $id = $this->id;
        Queue::push(function ($job) use ($id) {
            $cart = Cart::with('items.inventory')->findOrFail($id);
            $cart->items->each(function ($item) {
                $item->inventory->quantity -= $item->quantity;
                $item->inventory->save();
            });
        });
    }

    /**
     * Remove an item from the cart.
     *
     * @param  int                      $itemId
     * @return \Bedard\Shop\Models\CartItem
     */
    public function removeItem($itemId)
    {
        $item = $this->items()
            ->withTrashed()
            ->findOrFail($itemId);

        $item->delete();

        $this->syncItems();

        return $item;
    }

    /**
     * Restock the available inventories.
     *
     * @return void
     */
    public function restockInventories()
    {
        // throw an error if the cart is not closed
        if ($this->closed_at || $this->closed_by) {
            throw new Exception('Failed to restock inventories, the cart is not closed.');
        }

        $id = $this->id;
        Queue::push(function ($job) use ($id) {
            $cart = Cart::with('items.inventory')->findOrFail($id);
            $cart->items->each(function ($item) {
                $item->inventory->quantity += $item->quantity;
                $item->inventory->save();
            });
        });
    }

    /**
     * Save an item and sync the cart.
     *
     * @param  \Bedard\Shop\Models\Item     $item
     * @return \Bedard\Shop\Models\Item
     */
    public function saveItem($item)
    {
        $item->save();
        $this->syncItems();

        return $item;
    }

    /**
     * Select closed carts.
     *
     * @param  \October\Rain\Database\Builder $query
     * @return \October\Rain\Database\Builder
     */
    public function scopeIsClosed($query)
    {
        return $query->whereNotNull('closed_at');
    }

    /**
     * Select open carts.
     *
     * @param  \October\Rain\Database\Builder $query
     * @return \October\Rain\Database\Builder
     */
    public function scopeIsOpen($query)
    {
        return $query->whereNull('closed_at');
    }

    /**
     * Set an inventory.
     *
     * @param int $inventoryId
     * @param int $quantity
     */
    public function setInventory($inventoryId, $quantity)
    {
        $item = $this->getItemByInventoryId($inventoryId);

        $item->quantity = $quantity;

        return $this->saveItem($item);
    }

    /**
     * Sync the item_count and item_total columns.
     *
     * @return void
     */
    public function syncItems()
    {
        $total = 0;
        $this->load('items.inventory.product');

        foreach ($this->items as $item) {
            $total += $item->quantity * $item->product->base_price;
        }

        $this->item_total = $total;
        $this->item_count = $this->items()->sum('quantity') ?: 0;

        $this->save();
    }
}
