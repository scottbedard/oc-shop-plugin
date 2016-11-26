<?php

Route::group(['middleware' => '\Bedard\Shop\Classes\ApiMiddleware'], function () {
    //
    // Cart
    //
    Route::resource('api/bedard/shop/carts', 'Bedard\Shop\Api\Carts');

    //
    // Categories
    //
    Route::resource('api/bedard/shop/categories', 'Bedard\Shop\Api\Categories');
    Route::get('api/bedard/shop/categories/{category}/products', 'Bedard\Shop\Api\Categories@products');

    //
    // Products
    //
    Route::resource('api/bedard/shop/products', 'Bedard\Shop\Api\Products');
});
