<?php namespace Bedard\Shop\Controllers;

use Backend\Classes\Controller;
use Bedard\Shop\Models\Option;
use Bedard\Shop\Models\OptionValue;
use Exception;
use Response;

/**
 * Options Back-end Controller.
 */
class Options extends Controller
{
    public function createValue()
    {
        $data = input('value');
        if (! $data || ! is_array($data)) {
            return Response::make('Error', 422);
        }

        try {
            $sessionKey = uniqid('session_key', true);
            $value = OptionValue::create($data);
            $option = new Option;
            $option->values()->add($value, $sessionKey);

            return Response::make($value, 202);
        } catch (Exception $e) {
            return Response::make($e->getMessage(), 500);
        }
    }

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
