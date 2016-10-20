<?php

return [

    //
    // Plugin
    //
    'plugin' => [
        'details' => [
            'description' => 'An ecommerce platform for October CMS.',
            'name' => 'Shop',
        ],
        'navigation' => [
            'shop' => 'Shop',
        ],
        'permissions' => [
            'products' => 'Manage Products',
        ],
    ],

    //
    // Products
    //
    'products' => [
        'controller' => 'Products',
        'form' => [
            'create_title' => 'Create Product',
            'preview_title' => 'Preview Product',
            'update_title' => 'Update Product',
        ],
        'list' => [
            'create_button' => 'Create Product',
            'title' => 'Manage Products',
        ],
        'model' => 'Product',
    ],
];
