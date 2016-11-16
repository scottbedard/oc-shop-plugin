<?php namespace Bedard\Shop\Controllers;

use Bedard\Shop\Classes\BackendController;
use Bedard\Shop\Models\Filter;
use Exception;
use Response;

/**
 * Filters Back-end Controller.
 */
class Filters extends BackendController
{
    /**
     * Validate a filter.
     *
     * @return Response
     */
    public function validate()
    {
        $data = input('filter');
        if (! $data || ! is_array($data)) {
            return Response::make('Error', 422);
        }

        try {
            $filter = new Filter($data);
            $filter->validate();
        } catch (Exception $e) {
            return Response::make($e->getMessage(), 400);
        }

        return Response::make($filter, 200);
    }
}
