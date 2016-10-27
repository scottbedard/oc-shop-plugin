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
            'categories' => 'Manage Categories',
            'discounts' => 'Manage Discounts',
            'products' => 'Manage Products',
        ],
    ],

    //
    // Categories
    //
    'categories' => [
        'form' => [
            'create_title' => 'Create Category',
            'description' => 'Description',
            'is_active' => 'Category is active',
            'is_visible' => 'Show category in navigation',
            'name' => 'Name',
            'no_parent' => 'No parent',
            'tab_settings' => 'Settings',
            'parent' => 'Parent Category',
            'preview_title' => 'Preview Category',
            'slug' => 'Slug',
            'update_title' => 'Update Category',
        ],
        'list' => [
            'create_button' => 'Create Category',
            'name' => 'Name',
            'reorder_button' => 'Reorder Categories',
            'reorder_empty' => 'There are no categories to reorder.',
            'reorder_failure' => 'An error occured while attempting to reorder categories.',
            'reorder_success' => 'Successfully reordered categories.',
            'slug' => 'Slug',
            'title' => 'Manage Categories',
        ],
        'plural' => 'Categories',
        'singular' => 'Category',
    ],

    //
    // Discounts
    //
    'discounts' =>  [
        'form' => [
            'amount' => 'Discount amount',
            'amount_exact' => 'The exact amount to reduce prices by.',
            'amount_percentage' => 'The percentage to reduce prices by.',
            'method' => 'Discount method',
            'method_exact' => 'Exact amount',
            'method_percentage' => 'Percentage',
            'end_at' => 'End date',
            'name' => 'Name',
            'start_at' => 'Start date',
            'start_at_invalid' => 'The start date must be before the end date.',
        ],
        'list' => [
            'create_button' => 'Create Discount',
            'end_at' => 'End date',
            'end_at_null' => 'Never',
            'hide_expired' => 'Hide expired',
            'name' => 'Name',
            'start_at' => 'Start date',
            'start_at_null' => 'Immediately',
            'status' => 'Status',
            'status_active' => 'Active',
            'status_expired' => 'Expired',
            'status_upcoming' => 'Upcoming',
            'title' => 'Manage Discounts',
        ],
        'plural' => 'Discounts',
        'singular' => 'Discount',
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
            'name' => 'Name',
            'slug' => 'Slug',
            'title' => 'Manage Products',
        ],
        'plural' => 'Products',
        'singular' => 'Product',
    ],
];
