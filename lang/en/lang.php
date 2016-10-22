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
    // Categories
    //
    'categories' => [
        'form' => [
            'create_title' => 'Create Category',
            'name' => 'Name',
            'parent' => 'Parent Category',
            'preview_title' => 'Preview Category',
            'slug' => 'Slug',
            'update_title' => 'Update Category',
        ],
        'list' => [
            'create_button' => 'Create Category',
            'reorder_button' => 'Reorder Categories',
            'reorder_empty' => 'There are no categories to reorder.',
            'title' => 'Manage Categories',
        ],
        'plural' => 'Categories',
        'singular' => 'Category',
    ],

    //
    // Products
    //
    'products' => [
        'form' => [
            'create_title' => 'Create Product',
            'name' => 'Name',
            'preview_title' => 'Preview Product',
            'price' => 'Price',
            'slug' => 'Slug',
            'update_title' => 'Update Product',
        ],
        'list' => [
            'create_button' => 'Create Product',
            'title' => 'Manage Products',
        ],
        'plural' => 'Products',
        'singular' => 'Product',
    ],
];
