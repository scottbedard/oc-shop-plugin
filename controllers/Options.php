<?php namespace Bedard\Shop\Controllers;

use Bedard\Shop\Classes\BackendController;
use Bedard\Shop\Models\Option;
use Bedard\Shop\Models\OptionValue;
use Bedard\Shop\Models\Product;
use Exception;
use Response;

/**
 * Options Back-end Controller.
 */
class Options extends BackendController
{
    /**
     * Create an option with deferred bindings to a product.
     *
     * @return Response
     */
    public function create()
    {
        $data = input('option');
        if (! $data || ! is_array($data)) {
            return Response::make('Error', 422);
        }

        try {
            $sessionKey = uniqid('session_key', true);
            $option = Option::create($data);
            $product = new Product;
            $product->options()->add($option, $sessionKey);

            return Response::make($option, 202);
        } catch (Exception $e) {
            return Response::make($e->getMessage(), 500);
        }
    }

    /**
     * Create a value with deferred bindings to an option.
     *
     * @return Response
     */
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
