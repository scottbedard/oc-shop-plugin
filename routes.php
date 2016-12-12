<?php

Route::group(['middleware' => '\Bedard\Shop\Classes\ApiMiddleware'], function () {
    //
    // Cart
    //
    Route::get('api/bedard/shop/cart/exists', 'Bedard\Shop\Api\Cart@exists');
    Route::post('api/bedard/shop/cart/add', 'Bedard\Shop\Api\Cart@add');
    Route::delete('api/bedard/shop/cart/item/{item}', 'Bedard\Shop\Api\Cart@deleteItem');
    Route::patch('api/bedard/shop/cart/item/{item}', 'Bedard\Shop\Api\Cart@updateItem');
    Route::resource('api/bedard/shop/cart', 'Bedard\Shop\Api\Cart');

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
