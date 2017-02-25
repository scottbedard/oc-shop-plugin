<?php namespace Bedard\Shop\Controllers;

use Response;
use Exception;
use Bedard\Shop\Models\Inventory;
use Bedard\Shop\Classes\BackendController;

/**
 * Categories Back-end Controller.
 */
class Inventories extends BackendController
{
    /**
     * Create an inventory.
     *
     * @return Response
     */
    public function create()
    {
        try {
            // create our new inventory
            $data = input('inventory');
            unset($data['id']);
            $inventory = Inventory::create($data);
            // $model->load('values');

            // return the new option
            return Response::make($inventory, 200);
        } catch (Exception $e) {
            // if anything went wrong, send back the error message
            return Response::make($e->getMessage(), 500);
        }
    }

    /**
     * Validate an inventory.
     *
     * @return Response
     */
    public function validate()
    {
        try {
            $data = input('inventory');
            $inventory = Inventory::findOrNew($data['id']);
            $inventory->fill($data);
            $inventory->validate();

            return Response::make($inventory, 200);
        } catch (Exception $e) {
            return Response::make($e->getMessage(), 500);
        }
    }
}
