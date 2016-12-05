<?php namespace Bedard\Shop\Controllers;

use Response;
use Exception;
use Bedard\Shop\Models\Inventory;
use Bedard\Shop\Classes\BackendController;

/**
 * Inventories Back-end Controller.
 */
class Inventories extends BackendController
{
    /**
     * Validate an inventory.
     *
     * @return Response
     */
    public function validate()
    {
        $data = input('inventory');
        if (! $data || ! is_array($data)) {
            return Response::make('Error', 422);
        }

        try {
            $inventory = Inventory::firstOrNew(['id' => $data['id']]);
            $inventory->fill($data);
            $inventory->validate();
        } catch (Exception $e) {
            return Response::make($e->getMessage(), 400);
        }

        return Response::make($inventory, 200);
    }
}
