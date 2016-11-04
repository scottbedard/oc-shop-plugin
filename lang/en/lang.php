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
            'amount' => 'Amount',
            'create_button' => 'Create Discount',
            'end_at' => 'End date',
            'end_at_null' => 'Never',
            'hide_expired' => 'Hide expired',
            'name' => 'Name',
            'scope' => 'Scope',
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
    // Inventories
    //
    'inventories' => [
        'form' => [
            'create_button' => 'Create Inventory',
            'create_title' => 'Create Inventory',
            'default_inventory' => 'Default inventory',
            'in_stock_plural' => ':quantity in stock',
            'in_stock_singular' => ':quantity in stock',
            'loading_message' => 'Validating...',
            'option_placeholder' => 'Select :name',
            'out_of_stock' => 'Out of stock',
            'quantity' => 'Quantity',
            'sku' => 'Stock Keeping Unit',
            'sku_unique_error' => 'That stock keeping unit is already taken.',
            'update_title' => 'Update Inventory',
        ],
        'singular' => 'Inventory',
        'plural' => 'Inventories',
    ],

    //
    // Options
    //
    'options' => [
        'form' => [
            'create_button' => 'Create Option',
            'create_title' => 'Create Option',
            'delete_confirmation' => "Are you sure you want to delete this option?\nThis will also delete any inventories associated with it.",
            'loading_message' => 'Validating...',
            'name' => 'Name',
            'name_required_error' => 'All options must be given a name.',
            'pending_delete' => 'Pending Delete',
            'placeholder' => 'Placeholder',
            'update_title' => 'Update Option',
            'values' => 'Values',
            'values_placeholder' => 'Type value and press enter or tab',
        ],
        'singular' => 'Option',
        'plural' => 'Options',
    ],

    //
    // Products
    //
    'products' => [
        'form' => [
            'create_title' => 'Create Product',
            'duplicate_inventories_error' => 'Inventories options must be unique.',
            'duplicate_options_error' => 'Option names must be unique.',
            'name' => 'Name',
            'preview_title' => 'Preview Product',
            'base_price' => 'Base price',
            'slug' => 'Slug',
            'slug' => 'Slug',
            'tab_general' => 'General',
            'tab_options_inventories' => 'Options & Inventories',
            'update_title' => 'Update Product',
        ],
        'list' => [
            'create_button' => 'Create Product',
            'name' => 'Name',
            'normal_price' => 'Normally :price',
            'current_price' => 'Current price',
            'slug' => 'Slug',
            'title' => 'Manage Products',
        ],
        'plural' => 'Products',
        'singular' => 'Product',
    ],
];
