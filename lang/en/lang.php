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
            'settings' => 'Settings',
        ],
        'permissions' => [
            'api' => 'Manage API',
            'carts' => 'Manage Carts',
            'categories' => 'Manage Categories',
            'discounts' => 'Manage Discounts',
            'orders' => 'Manage Orders',
            'products' => 'Manage Products',
        ],
    ],

    //
    // API
    //
    'api' => [
        'category' => [
            'category_select' => 'Select category columns',
            'load_products' => 'Load first page of products',
            'load_products_thumbnails' => 'Load product thumbnails',
            'load_thumbnails' => 'Load category thumbnails',
            'products_select' => 'Select product columns',
            'select_created_at' => 'Created date',
            'select_description_html' => 'Description (html)',
            'select_description_plain' => 'Description (plain)',
            'select_id' => 'ID',
            'select_is_visible' => 'Visible status',
            'select_name' => 'Name',
            'select_parent_id' => 'Parent ID',
            'select_product_columns' => 'Product columns',
            'select_product_order' => 'Product order',
            'select_product_rows' => 'Product rows',
            'select_product_sort_column' => 'Product sort column',
            'select_product_sort_direction' => 'Product sort direction',
            'select_slug' => 'Slug',
            'select_sort_order' => 'Sort order',
            'select_updated_at' => 'Updated date',
        ],
        'categories' => [
            'hide_empty' => 'Hide empty categories',
            'load_thumbnails' => 'Load thumbnails',
            'select' => 'Select columns',
        ],
        'description' => 'Manage API settings.',
        'form' => [
            'is_enabled' => 'API Endpoints',
            'is_enabled_comment' => 'When turned on, HTTP endpoints will be accessible. For traditional themes using October components, this can be left off.',
        ],
        'label' => 'API',
        'product' => [
            'select_base_price' => 'Base price',
            'select_created_at' => 'Created date',
            'select_current_price' => 'Current price',
            'select_description_html' => 'Description (html)',
            'select_description_plain' => 'Description (plain)',
            'select_id' => 'ID',
            'select_name' => 'Name',
            'select_slug' => 'Slug',
            'select_updated_at' => 'Updated date',
        ],
    ],

    //
    // Carts
    //
    'carts' => [
        'label' => 'Shopping carts',
        'description' => 'Manage shopping cart settings.',
    ],

    //
    // Categories
    //
    'categories' => [
        'form' => [
            'create_filter' => 'Create Filter',
            'create_title' => 'Create Category',
            'description' => 'Description',
            'filter_frequency' => 'Filter frequency',
            'filter_frequency_comment' => 'Time in minutes before refreshing products.',
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
            'right' => 'Value field',
            'right_custom' => 'Custom value',
            'right_days_ago' => 'Days ago',
            'right_n_days_ago_singular' => ':n day ago',
            'right_n_days_ago_plural' => ':n days ago',
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
            'sku' => 'Stock Keeping Unit',
            'sku_unique_error' => 'That stock keeping unit is already taken.',
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
            'is_enabled' => 'Enabled status',
            'is_enabled_comment' => 'When turned off, it will be as if the product does not exist.',
            'name' => 'Name',
            'preview_title' => 'Preview Product',
            'slug' => 'Slug',
            'tab_images' => 'Images',
            'tab_general' => 'General',
            'tab_options_inventories' => 'Options & Inventories',
            'thumbnails' => 'Thumbnails',
            'update_title' => 'Update Product',
        ],
        'list' => [
            'create_button' => 'Create Product',
            'inventory' => 'Inventory',
            'name' => 'Name',
            'normal_price' => 'Normally :price',
            'current_price' => 'Current price',
            'slug' => 'Slug',
            'status' => 'Status',
            'status_disabled' => 'Disabled',
            'status_out_of_stock' => 'Out of stock',
            'status_normal' => 'Normal',
            'status_discounted' => ':discount_name',
            'status_discounted_tooltip' => 'Discount ends :end_at',
            'title' => 'Manage Products',
        ],
        'plural' => 'Products',
        'singular' => 'Product',
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
