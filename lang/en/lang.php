<?php

return [
    //
    // api
    //
    'api' => [
        'form' => [
            'api_is_enabled_comment' => 'When off, there will be no access to API endpoints.',
            'api_is_enabled' => 'API Endpoints',
            'columns' => 'Columns',
            'endpoint' => 'Endpoint',
            'relationships_comment' => 'Type a relationship to eager load, then press "enter" or "tab".',
            'relationships_placeholder' => 'Enter relationship',
            'relationships' => 'Relationships',
        ],
        'label' => 'API Endpoints',
        'title' => 'Configure API endpoints',
    ],

    //
    // carts
    //
    'carts' => [
        'form' => [
            'history' => 'History',
            'return_to_list' => 'Return to carts list',
        ],
        'list' => [
            'created_at' => 'Created',
            'manage_statuses' => 'Manage statuses',
            'status' => 'Status',
            'updated_at' => 'Updated',
        ],
        'plural' => 'Carts',
        'singular' => 'Cart',
    ],

    //
    // categories
    //
    'categories' => [
        'form' => [
            'description' => 'Description',
            'name' => 'Name',
            'slug' => 'Slug',
            'tab_general' => 'General',
        ],
        'list' => [
            'name' => 'Name',
            'reorder_button' => 'Reorder',
            'slug' => 'Slug',
            'title' => 'Manage Categories',
        ],
        'reorder' => [
            'return_button' => 'Return to categories',
        ],
        'singular' => 'Category',
        'plural' => 'Categories',
    ],

    //
    // console
    //
    'console' => [
        'abandon' => [
            'description' => 'Process carts that have been abandoned',
            'success' => 'Processed abandoned carts',
        ],
    ],

    //
    // drivers
    //
    'drivers' => [
        'form' => [
            'is_enabled_true' => 'Enabled',
            'is_enabled_false' => 'Disabled',
            'status_selector_placeholder' => 'Select a status',
        ],
        'nopayment' => [
            'event_complete' => 'Checkout complete',
            'event_complete_descrition' => 'This event fires when a user completes the checkout process.',
            'events_tab' => 'Events',
            'message' => 'This driver does not charge the user.',
            'name' => 'No Payment',
        ],
    ],

    //
    // inventories
    //
    'inventories' => [
        'form' => [
            'default_exists_error' => 'A default inventory already exists for this product.',
            'delete_value_title' => 'This option is flagged for deletion.',
            'quantity_negative_error' => 'Quantities must be zero or greater.', // <- @todo: match october error messaging
            'quantity' => 'Quantity',
            'sku_placeholder' => 'None',
            'sku_unique_error' => 'An inventory with that sku already exists.',
            'sku' => 'Stock keeping unit',
            'value_collision_error' => 'An inventory with those options already exists.',
        ],
        'list' => [
            'default_name' => 'Default inventory',
            'delete_option_warning' => 'The inventories of deleted options may not be edited.',
            'delete_title' => 'Delete inventory',
            'delete_warning' => 'Inventories flagged for deletion may not be edited.',
            'multiple_in_stock' => ':quantity in stock',
            'out_of_stock' => 'Out of stock',
            'restore_collision_default' => 'This inventory cannot be restored because a default inventory already exists.',
            'restore_collision_values' => 'This inventory cannot be restored because an inventory with those options already exists.',
            'restore_title' => 'Restore inventory',
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
            'delete_value_warning' => 'Values flagged for deletion may not be edited.',
            'name' => 'Name',
            'placeholder' => 'Placeholder',
            'values_delete_title' => 'Delete value',
            'values_placeholder' => 'Type a value and press "enter" or "tab"',
            'values_reorder_title' => 'Click and drag to reorder values',
            'values_required' => 'Options must have at least one value.',
            'values_restore_title' => 'Restore value',
            'values_unique' => 'Value names must be unique.',
            'values' => 'Values',
        ],
        'list' => [
            'delete_title' => 'Delete option',
            'delete_warning' => 'Options flagged for deletion may not be edited.',
            'reorder_title' => 'Click and drag to reorder options',
            'restore_title' => 'Restore option',
        ],
        'singular' => 'Option',
        'plural' => 'Options',
    ],

    //
    // payment
    //
    'payment' => [
        'form' => [
            'configure' => 'Configure payment drivers',
        ],
        'label' => 'Payment drivers',
        'title' => 'Configure payment drivers',
    ],

    //
    // plugin
    //
    'plugin' => [
        'description' => 'An ecommerce platform for October CMS.',
        'name' => 'Shop',
        'permissions' => [
            'carts' => 'Manage Carts',
            'categories' => 'Manage Categories',
            'products' => 'Manage Products',
            'settings' => 'Manage Settings',
            'statuses' => 'Manage Statuses',
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
            'images' => 'Images',
            'is_enabled_comment' => 'When enabled, this product will be available for purchase.',
            'is_enabled' => 'Enabled',
            'name' => 'Name',
            'slug' => 'Slug',
            'tab_general' => 'General',
            'tab_images' => 'Images',
            'tab_options_inventories' => 'Options & Inventories',
            'thumbnails' => 'Thumbnails',
        ],
        'plural' => 'Products',
        'singular' => 'Product',
    ],

    //
    // settings
    //
    'settings' => [
        'form' => [
            'cart_lifespan_1_day' => '1 day',
            'cart_lifespan_1_hour' => '1 hour',
            'cart_lifespan_1_week' => '1 week',
            'cart_lifespan_12_hours' => '12 hours',
            'cart_lifespan_2_days' => '2 days',
            'cart_lifespan_3_days' => '3 days',
            'cart_lifespan_6_hours' => '6 hours',
            'cart_lifespan_description' => 'Carts that have not been touched for this amount of time will be considered abandoned.',
            'cart_lifespan' => 'Cart lifespan',
        ],
        'label' => 'Settings',
        'plural' => 'Settings',
        'singular' => 'Setting',
        'title' => 'General shop configuration',
    ],

    //
    // statuses
    //
    'statuses' => [
        'list' => [
            'color' => 'Color',
            'icon' => 'Icon',
            'is_abandoned' => 'Abandoned',
            'is_default' => 'Default',
            'is_reducing' => 'Reducing',
            'name' => 'Name',
            'return_to_carts' => 'Return to carts',
            'title' => 'Manage Statuses',
        ],
        'form' => [
            'color' => 'Color',
            'icon' => 'Icon',
            'is_abandoned_comment' => 'The abandoned status will be applied to inactive carts.',
            'is_abandoned' => 'Abandoned',
            'is_default_abandoned_exception' => 'A status cannot be both the default and abandoned status.',
            'is_default_comment' => 'The default status will be the first status of a cart.',
            'is_default' => 'Default',
            'is_default_removed' => 'The default status cannot be removed. Instead, try setting a different status as the default.',
            'is_reducing' => 'Reduce inventory',
            'is_reducing_comment' => 'When on, this will status will subtract the cart\'s items from the available inventory.',
            'name' => 'Name',
        ],
        'plural' => 'Statuses',
        'presets' => [
            'abandoned' => 'Abandoned',
            'awaiting_payment' => 'Awaiting payment',
            'complete' => 'Complete',
            'open' => 'Open',
            'payment_received' => 'Payment received',
        ],
        'singular' => 'Status',
    ],
];
