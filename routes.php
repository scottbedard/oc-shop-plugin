<?php

Route::group(['middleware' => '\Bedard\Shop\Classes\ApiMiddleware'], function() {

    Route::resource('api/bedard/shop/categories', 'Bedard\Shop\Api\Categories');

});
