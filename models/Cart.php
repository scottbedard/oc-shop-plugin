<?php namespace Bedard\Shop\Models;

use Bedard\Shop\Classes\Driver;
use Carbon\Carbon;
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
        'address_id' => null,
        'created_at' => null,
        'id' => null,
        'item_count' => 0,
        'item_total' => 0,
        'token' => null,
        'update_count' => 0,
        'updated_at' => null,
        'user_id' => null,
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
        'abandoned_at',
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
        'address_id',
        'closed_at',
        'item_count',
        'item_total',
        'update_count',
        'user_id',
    ];

    /**
     * @var array Relations
     */
    public $belongsTo = [
        'address' => [
            'Bedard\Shop\Models\Address',
        ],
        'user' => [
            'RainLab\User\Models\User',
        ],
    ];

    public $belongsToMany = [
        'statuses' => [
            'Bedard\Shop\Models\Status',
            'order' => 'pivot_created_at desc',
            'pivot' => [
                'created_at',
                'driver',
            ],
            'table' => 'bedard_shop_cart_status',
        ],
    ];

    public $hasMany = [
        'items' => [
            'Bedard\Shop\Models\CartItem',
        ],
    ];

    /**
     * Abandon a cart.
     *
     * @return void
     */
    public function abandon()
    {
        $status = Status::isAbandoned()->first();

        if ($status) {
            $this->setStatus($status);
        }

        $this->abandoned_at = Carbon::now();
        $this->save();
    }

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
            $this->setStatus($status);
        }
    }

    /**
     * Close a cart.
     *
     * @return bool
     */
    public function close()
    {
        $this->closed_at = Carbon::now();

        return $this->save();
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
     * Process carts that have been abandoned.
     *
     * @return void
     */
    public static function processAbandoned()
    {
        self::isAbandoned()->get()->each(function ($cart) {
            $cart->abandon();
        });
    }

    /**
     * Reduce the available inventory.
     *
     * @return void
     */
    public function reduceAvailableInventory()
    {
        $id = $this->id;
        Queue::push(function ($job) use ($id) {
            $cart = Cart::with('items.inventory')->findOrFail($id);

            $cart->items->each(function ($item) {
                $item->reduceAvailableInventory();
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
     * Select abandoned carts.
     *
     * @param  \October\Rain\Database\Builder $query
     * @return \October\Rain\Database\Builder
     */
    public function scopeIsAbandoned($query)
    {
        $lifespan = Settings::getLifespan();

        return $query->where('updated_at', '<', Carbon::now()->subMinutes($lifespan));
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
        return $query
            ->whereNull('abandoned_at')
            ->whereNull('closed_at');
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
     * Set a cart status.
     *
     * @param \Bedard\Shop\Models\Status        $status
     * @param \Bedard\Shop\Classes\Driver|null  $driver
     */
    public function setStatus(Status $status, Driver $driver = null)
    {
        $this->statuses()->attach($status, [
            'driver' => $driver ? get_class($driver) : null,
        ]);

        // reduce inventories if neccessary
        if ($status->is_reducing) {
            $id = $this->id;
            Queue::push(function () use ($id) {
                $cart = Cart::findOrFail($id);
                $cart->reduceAvailableInventory();
            });
        }
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
