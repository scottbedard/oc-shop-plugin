<?php namespace Bedard\Shop\Controllers;

use BackendMenu;
use Bedard\Shop\Classes\BackendController;
use Bedard\Shop\Models\Option;
use Bedard\Shop\Models\Product;
use Exception;
use Response;

/**
 * Categories Back-end Controller.
 */
class Options extends BackendController
{
    /**
     * Create a new option.
     *
     * @return Response
     */
    public function create()
    {
        // create our new option
        $option = input('option');
        unset($option['id']);
        $option = Option::create($option);

        // attach it to the product with a deferred binding
        $product = new Product;
        $product->options()->add($option, uniqid('session_key', true));

        // return the option
        return Response::make($option, 200);
    }
}
