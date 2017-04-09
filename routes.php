<?php

Route::group(['middleware' => '\Bedard\Shop\Classes\ApiMiddleware'], function () {
    // cart
    Route::get('api/bedard/shop/cart', 'Bedard\Shop\Api\CartApi@index');

    // categories
    Route::resource('api/bedard/shop/categories', 'Bedard\Shop\Api\CategoriesApi');

    // products
    Route::resource('api/bedard/shop/products', 'Bedard\Shop\Api\ProductsApi');
});
