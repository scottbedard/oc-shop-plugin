<?php

Route::group(['middleware' => '\Bedard\Shop\Classes\ApiMiddleware'], function () {
    Route::resource('api/bedard/shop/products', 'Bedard\Shop\Api\ProductsApi');
});
