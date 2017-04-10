<?php namespace Bedard\Shop\Models;

use Bedard\Shop\Models\Inventory;
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
        'item_count' => 0,
        'item_total' => 0,
        'token' => null,
        'updated_at' => null,
    ];

    /**
     * @var array Attribute casting
     */
    protected $casts = [
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
        'item_count',
        'item_total',
    ];

    /**
     * @var array Relations
     */
    public $hasMany = [
        'items' => [
            'Bedard\Shop\Models\CartItem',
        ],
    ];

    /**
     * Add inventory to the cart.
     *
     * @param  integer $inventoryId
     * @param  integer $quantity
     */
    public function addInventory($inventoryId, $quantity = 1)
    {
        $inventory = Inventory::findOrFail($inventoryId);

        $item = $this->items()->firstOrNew([
            'cart_id' => $this->id,
            'inventory_id' => $inventoryId,
            'product_id' => $inventory->product_id,
        ]);

        $item->quantity += $quantity;

        if ($item->quantity > $inventory->quantity) {
            $item->quantity = $inventory->quantity;
        }

        $item->save();
        $this->syncItems();

        return $item;
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
     * Delete an inventory from the cart.
     *
     * @param  integer $inventoryId
     * @param  integer $quantity
     */
    public function deleteInventory($inventoryId)
    {
        $this->items()->whereInventoryId($inventoryId)->delete();
        $this->syncItems();
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
     * Set an inventory.
     *
     * @param integer $inventoryId
     * @param integer $quantity
     */
    public function setInventory($inventoryId, $quantity)
    {
        $inventory = Inventory::findOrFail($inventoryId);

        $item = $this->items()->firstOrNew([
            'cart_id' => $this->id,
            'inventory_id' => $inventoryId,
            'product_id' => $inventory->product_id,
        ]);

        $item->quantity = $quantity;

        if ($item->quantity > $inventory->quantity) {
            $item->quantity = $inventory->quantity;
        }

        $item->save();
        $this->syncItems();

        return $item;
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
