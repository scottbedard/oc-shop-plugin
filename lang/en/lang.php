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
            'settings' => 'Settings',
            'shop' => 'Shop',
        ],
        'permissions' => [
            'api' => 'Manage API',
            'carts' => 'Manage Carts',
            'categories' => 'Manage Categories',
            'customers' => 'Manage Customers',
            'discounts' => 'Manage Discounts',
            'orders' => 'Manage Orders',
            'products' => 'Manage Products',
            'promotions' => 'Manage Promotions',
            'shipping' => 'Manage Shipping',
        ],
    ],

    //
    // API
    //
    'api' => [
        'category' => [
            'load_thumbnails' => 'Load product thumbnails',
        ],
        'categories' => [
            'hide_empty' => 'Hide empty categories',
            'load_products_count' => 'Load products count',
            'load_thumbnails' => 'Load category thumbnails',
        ],
        'description' => 'Manage API settings.',
        'form' => [
            'is_enabled_comment' => 'Traditional themes using October components should leave this turned off.',
            'is_enabled' => 'API Endpoints',
        ],
        'label' => 'API',
        'product' => [

        ],
    ],

    //
    // Carts
    //
    'carts' => [
        'label' => 'Shopping carts',
        'description' => 'Manage shopping cart settings.',
        'form' => [
            'lifespan_1_day' => '1 day',
            'lifespan_1_month' => '1 month',
            'lifespan_1_week' => '1 week',
            'lifespan_12_hours' => '12 hours',
            'lifespan_forever' => 'Forever',
            'lifespan_label' => 'Cart lifespan',
        ],
    ],

    //
    // Categories
    //
    'categories' => [
        'form' => [
            'create_filter' => 'Create Filter',
            'create_title' => 'Create Category',
            'description' => 'Description',
            'filter_frequency_comment' => 'Time in minutes before refreshing products.',
            'filter_frequency' => 'Filter frequency',
            'filters' => 'Filters',
            'is_active' => 'Category is active',
            'is_visible' => 'Show category in navigation',
            'name' => 'Name',
            'no_parent' => 'No parent',
            'parent' => 'Parent Category',
            'preview_title' => 'Preview Category',
            'product_columns' => 'Columns',
            'product_order_created_at_asc' => 'Date created (oldest first)',
            'product_order_created_at_desc' => 'Date created (newest first)',
            'product_order_custom' => 'Custom',
            'product_order_name_asc' => 'Name (ascending)',
            'product_order_name_desc' => 'Name (descending)',
            'product_order_price_asc' => 'Price (ascending)',
            'product_order_price_desc' => 'Price (descending)',
            'product_order_updated_at_asc' => 'Date updated (oldest first)',
            'product_order_updated_at_desc' => 'Date updated (newest first)',
            'product_order' => 'Product order',
            'product_rows_none' => 'Display all products',
            'product_rows' => 'Rows per page',
            'product_sort' => 'Product sorting',
            'slug' => 'Slug',
            'tab_filters' => 'Filters',
            'tab_products' => 'Products',
            'tab_settings' => 'Settings',
            'tab_thumbnails' => 'Thumbnails',
            'thumbnails' => 'Thumbnails',
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
    // Customers
    //
    'customers' => [
        'form' => [
            'create_title' => 'Create Customer',
            'email' => 'Email address',
            'name' => 'Name',
            'update_title' => 'Update Customer',
        ],
        'list' => [
            'create_button' => 'Create Customer',
            'email' => 'Email address',
            'name' => 'Name',
            'title' => 'Manage Customers',
        ],
        'plural' => 'Customers',
        'singular' => 'Customer',
    ],

    //
    // Discounts
    //
    'discounts' =>  [
        'form' => [
            'amount_exact' => 'The exact amount to reduce prices by.',
            'amount_percentage' => 'The percentage to reduce prices by.',
            'amount' => 'Discount amount',
            'categories_empty' => 'There are no categories.',
            'end_at' => 'End date',
            'method_exact' => 'Exact amount',
            'method_percentage' => 'Percentage',
            'method' => 'Discount method',
            'name' => 'Name',
            'products_empty' => 'There are no products.',
            'start_at' => 'Start date',
        ],
        'list' => [
            'amount' => 'Amount',
            'create_button' => 'Create Discount',
            'end_at_null' => 'Never',
            'end_at' => 'End date',
            'hide_expired' => 'Hide expired',
            'name' => 'Name',
            'scope' => 'Scope',
            'start_at_null' => 'Immediately',
            'start_at' => 'Start date',
            'status_active' => 'Active',
            'status_expired' => 'Expired',
            'status_upcoming' => 'Upcoming',
            'status' => 'Status',
            'title' => 'Manage Discounts',
        ],
        'plural' => 'Discounts',
        'singular' => 'Discount',
    ],

    //
    // Filters
    //
    'filters' => [
        'form' => [
            'actual_price' => 'Actual price',
            'base_price' => 'Base price',
            'comparator_equal_to' => 'Equal to',
            'comparator_greater_than_or_equal' => 'Greater than or equal to',
            'comparator_greater_than' => 'Greater than',
            'comparator_less_than_or_equal' => 'Less than or equal to',
            'comparator_less_than' => 'Less than',
            'comparator_not_equal_to' => 'Does not equal',
            'comparator' => 'Comparator',
            'create' => 'Create filter',
            'left_created_at' => 'Created date',
            'left_updated_at' => 'Updated date',
            'left' => 'Relevant field',
            'loading_message' => 'Saving filter...',
            'right_custom' => 'Custom value',
            'right_days_ago' => 'Days ago',
            'right_n_days_ago_plural' => ':n days ago',
            'right_n_days_ago_singular' => ':n day ago',
            'right' => 'Value field',
            'update' => 'Update filter',
        ],
    ],

    //
    // Inventories
    //
    'inventories' => [
        'form' => [
            'create_button' => 'Create Inventory',
            'create_title' => 'Create Inventory',
            'loading_message' => 'Saving inventory...',
            'option_placeholder' => 'Select :name',
            'quantity' => 'Quantity',
            'sku_unique_error' => 'That stock keeping unit is already taken.',
            'sku' => 'Stock Keeping Unit',
            'update_title' => 'Update Inventory',
        ],
        'list' => [
            'default' => 'Default inventory',
            'in_stock_plural' => ':quantity in stock',
            'in_stock_singular' => ':quantity in stock',
            'out_of_stock' => 'Out of stock',
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
            'loading_message' => 'Saving option...',
            'name_required_error' => 'All options must be given a name.',
            'name' => 'Name',
            'pending_delete' => 'Pending Delete',
            'placeholder' => 'Placeholder',
            'update_title' => 'Update Option',
            'values_placeholder' => 'Type value and press enter or tab',
            'values' => 'Values',
        ],
        'singular' => 'Option',
        'plural' => 'Options',
    ],

    //
    // Orders
    //
    'orders' => [
        'list' => [
            'title' => 'Manage Orders',
        ],
        'plural' => 'Orders',
        'singular' => 'Order',
    ],

    //
    // Products
    //
    'products' => [
        'form' => [
            'base_price' => 'Base price',
            'create_title' => 'Create Product',
            'description' => 'Description',
            'duplicate_inventories_error' => 'Inventories options must be unique.',
            'duplicate_options_error' => 'Option names must be unique.',
            'images' => 'Images',
            'is_enabled_comment' => 'When turned off, it will be as if the product does not exist.',
            'is_enabled' => 'Enabled status',
            'name' => 'Name',
            'preview_title' => 'Preview Product',
            'slug' => 'Slug',
            'tab_general' => 'General',
            'tab_images' => 'Images',
            'tab_options_inventories' => 'Options & Inventories',
            'thumbnails' => 'Thumbnails',
            'update_title' => 'Update Product',
        ],
        'list' => [
            'create_button' => 'Create Product',
            'current_price' => 'Current price',
            'inventory' => 'Inventory',
            'name' => 'Name',
            'normal_price' => 'Normally :price',
            'slug' => 'Slug',
            'status_disabled' => 'Disabled',
            'status_discounted_indefinite_tooltip' => 'Discount runs indefinitely',
            'status_discounted_tooltip' => 'Discount ends :end_at',
            'status_discounted' => ':discount_name',
            'status_normal' => 'Normal',
            'status_out_of_stock' => 'Out of stock',
            'status' => 'Status',
            'title' => 'Manage Products',
        ],
        'plural' => 'Products',
        'singular' => 'Product',
    ],

    //
    // Promotions
    //
    'promotions' => [
        'form' => [
            'amount_exact' => 'The exact amount to reduce the cart total by.',
            'amount_percentage' => 'The percentage to reduce the cart total by.',
            'amount' => 'Discount amount',
            'create_title' => 'Create Promotion',
            'end_at' => 'End date',
            'message' => 'Message',
            'method_exact' => 'Exact amount',
            'method_percentage' => 'Percentage',
            'method' => 'Discount method',
            'minimum_cart_value' => 'Minimum cart value',
            'name' => 'Name',
            'start_at' => 'Start date',
            'update_title' => 'Update Promotion',
        ],
        'list' => [
            'amount' => 'Amount',
            'create_button' => 'Create Promotion',
            'end_at_null' => 'Never',
            'end_at' => 'End date',
            'name' => 'Name',
            'start_at_null' => 'Immediately',
            'start_at' => 'Start date',
            'title' => 'Manage Promotions',
        ],
        'plural' => 'Promotions',
        'singular' => 'Promotion',
    ],

    //
    // Shipping
    //
    'shipping' => [
        'description' => 'Manage shipping calculators.',
        'label' => 'Shipping calculators',
    ],

    //
    // Traits
    //
    'traits' => [
        'startendable' => [
            'start_at_invalid' => 'The start date must be before the end date.',
        ],
    ],

    //
    // UI
    //
    'ui' => [
        'dropdown' => [
            'no_results' => 'No results found.',
        ],
    ],
];
