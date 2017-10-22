<?php namespace Bedard\Shop\Controllers;

use Bedard\Shop\Classes\BackendController;
use Bedard\Shop\Models\Inventory;
use Exception;
use Response;

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
            $inventory->load('values');

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
        $data = input();

        $inventory = Inventory::findOrNew($data['id']);

        $inventory->fill($data);

        try {
            $inventory->validate();
        } catch (Exception $e) {
            return response($e->getMessage(), 422);
        }

        return response('Ok');
    }
}
