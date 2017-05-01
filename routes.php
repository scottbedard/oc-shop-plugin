<?php

Route::group(['middleware' => '\Bedard\Shop\Classes\ApiMiddleware'], function () {
    // cart
    Route::get('api/bedard/shop/cart', 'Bedard\Shop\Api\CartApi@index');
    Route::get('api/bedard/shop/cart/touch', 'Bedard\Shop\Api\CartApi@touch');
    Route::delete('api/bedard/shop/cart/item', 'Bedard\Shop\Api\CartApi@remove');
    Route::patch('api/bedard/shop/cart/item', 'Bedard\Shop\Api\CartApi@update');
    Route::post('api/bedard/shop/cart/item', 'Bedard\Shop\Api\CartApi@add');

    // categories
    Route::resource('api/bedard/shop/categories', 'Bedard\Shop\Api\CategoriesApi');

    // products
    Route::resource('api/bedard/shop/products', 'Bedard\Shop\Api\ProductsApi');
});
