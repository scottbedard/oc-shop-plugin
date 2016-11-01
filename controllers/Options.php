<?php namespace Bedard\Shop\Controllers;

use Backend\Classes\Controller;
use Bedard\Shop\Models\Option;
use Exception;
use Response;

/**
 * Options Back-end Controller.
 */
class Options extends Controller
{
    /**
     * Validate an option.
     *
     * @return Response
     */
    public function validate()
    {
        $data = input('option');
        if (! $data || ! is_array($data)) {
            return Response::make('Error', 422);
        }

        try {
            $option = new Option($data);
            $option->validate();
        } catch (Exception $e) {
            return Response::make($e->getMessage(), 400);
        }

        return Response::make($option, 200);
    }
}
