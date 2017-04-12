<?php namespace Bedard\Shop\Models;

use Model;

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
        'id' => null,
        'created_at' => null,
        'update_count' => 0,
        'item_count' => 0,
        'item_total' => 0,
        'token' => null,
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
     * @var array Guarded fields
     */
    protected $guarded = ['*'];

    /**
     * @var array Fillable fields
     */
    protected $fillable = [
        'update_count',
        'item_count',
        'item_total',
    ];

    /**
     * @var array Relations
     */
    public $belongsToMany = [
        'statuses' => [
            'Bedard\Shop\Models\Status',
            'order' => 'pivot_created_at asc',
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
        $this->createOpenStatus();
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
     * Create an open status.
     *
     * @return void
     */
    protected function createOpenStatus()
    {
        $this->statuses()->attach(Status::find(1));
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
