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
        'form' => [
            'collision_default' => 'A default inventory is already defined.',
            'collision_values' => 'An inventory with these options is already defined.',
            'quantity' => 'Quantity',
            'sku' => 'Stock keeping unit',
            'sku_unique' => 'An inventory with that sku already exists.',
        ],
        'list' => [
            'default_name' => 'Default inventory',
            'delete_title' => 'Delete inventory',
            'deleted_option_warning' => 'Cannot edit inventory because it has an option that is being deleted.',
            'multiple_in_stock' => ':quantity in stock',
            'out_of_stock' => 'Out of stock',
            'restore_collision_default' => 'This inventory cannot be restored because a default inventory already exists.',
            'restore_collision_values' => 'This inventory cannot be restored because an inventory with those options already exists.',
            'restore_title' => 'Restore inventory',
            'restore_warning' => 'Deleted inventories cannot be edited, it must first be restored.',
            'single_in_stock' => ':quantity in stock',
        ],
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
            'values_delete_title' => 'Delete value',
            'values_placeholder' => 'Type a value and press "enter" or "tab"',
            'values_required' => 'Options must have at least one value.',
            'values_reorder_title' => 'Click and drag to reorder values',
            'values_restore_title' => 'Restore value',
            'values_unique' => 'Value names must be unique.',
        ],
        'list' => [
            'delete_title' => 'Delete option',
            'reorder_title' => 'Click and drag to reorder options',
            'restore_title' => 'Restore option',
            'restore_warning' => 'Deleted options cannot be edited, it must first be restored.',
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
            'in_stock_plural' => ':quantity in stock',
            'in_stock_singular' => ':quantity in stock',
            'inventory' => 'Inventory',
            'name' => 'Name',
            'out_of_stock' => 'Out of stock',
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
