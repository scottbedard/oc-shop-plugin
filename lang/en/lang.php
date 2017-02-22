<?php

return [
    //
    // categories
    //
    'categories' => [
        'list' => [
            'name' => 'Name',
            'title' => 'Manage Categories',
            'slug' => 'Slug',
        ],
        'form' => [
            'name' => 'Name',
            'slug' => 'Slug',
            'tab_general' => 'General',
        ],
        'singular' => 'Category',
        'plural' => 'Categories',
    ],

    //
    // inventories
    //
    'inventories' => [
        'singular' => 'Inventory',
        'plural' => 'Inventories',
    ],

    //
    // options
    //
    'options' => [
        'form' => [
            'name' => 'Name',
            'placeholder' => 'Placeholder',
            'values' => 'Values',
            'values_placeholder' => 'Type a value and press "enter" or "tab"',
            'values_unique' => 'Value names must be unique.',
        ],
        'singular' => 'Option',
        'plural' => 'Options',
    ],

    //
    // plugin
    //
    'plugin' => [
        'description' => 'An ecommerce platform for October CMS.',
        'name' => 'Shop',
        'permissions' => [
            'categories' => 'Manage Categories',
            'products' => 'Manage Products',
        ],
    ],

    //
    // products
    //
    'products' => [
        'list' => [
            'name' => 'Name',
            'price' => 'Price',
            'slug' => 'Slug',
            'status_disabled' => 'Disabled',
            'status_enabled' => 'Enabled',
            'status' => 'Status',
            'title' => 'Manage Products',
        ],
        'form' => [
            'base_price' => 'Base price',
            'description' => 'Description',
            'is_enabled_comment' => 'When enabled, this product will be available for purchase.',
            'is_enabled' => 'Enabled',
            'name' => 'Name',
            'slug' => 'Slug',
            'tab_general' => 'General',
            'tab_options_inventories' => 'Options & Inventories',
        ],
        'plural' => 'Products',
        'singular' => 'Product',
    ],
];
