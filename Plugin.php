<?php namespace Bedard\Shop;

use Backend;
use System\Classes\PluginBase;

/**
 * Shop Plugin Information File.
 */
class Plugin extends PluginBase
{
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'bedard.shop::lang.plugin.details.name',
            'description' => 'bedard.shop::lang.plugin.details.description',
            'author'      => 'Scott Bedard',
            'icon'        => 'icon-shopping-cart',
        ];
    }

    /**
     * Register form widgets.
     *
     * @return array
     */
    public function registerFormWidgets()
    {
        return [
            'Bedard\Shop\FormWidgets\CategoryFilters' => 'category-filters',
            'Bedard\Shop\FormWidgets\DriverConfig' => 'driver-config',
            'Bedard\Shop\FormWidgets\OptionsInventories' => 'options-inventories',
            'Bedard\Shop\FormWidgets\ProductOrder' => 'product-order',
            'Owl\FormWidgets\Knob\Widget' => 'owl-knob',
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return [
            'shop' => [
                'icon'        => 'icon-shopping-cart',
                'label'       => 'bedard.shop::lang.plugin.navigation.shop',
                'order'       => 500,
                'permissions' => ['bedard.shop.*'],
                'url'         => Backend::url('bedard/shop/orders'),
                'sideMenu' => [
                    'orders' => [
                        'label'         => 'bedard.shop::lang.orders.plural',
                        'icon'          => 'icon-shopping-cart',
                        'url'           => Backend::url('bedard/shop/orders'),
                        'permissions'   => ['bedard.shop.orders.*'],
                    ],
                    'customers' => [
                        'label'         => 'bedard.shop::lang.customers.plural',
                        'icon'          => 'icon-users',
                        'url'           => Backend::url('bedard/shop/customers'),
                        'permissions'   => ['bedard.shop.customers.*'],
                    ],
                    'products' => [
                        'label'         => 'bedard.shop::lang.products.plural',
                        'icon'          => 'icon-cubes',
                        'url'           => Backend::url('bedard/shop/products'),
                        'permissions'   => ['bedard.shop.products.*'],
                    ],
                    'categories' => [
                        'label'         => 'bedard.shop::lang.categories.plural',
                        'icon'          => 'icon-folder-o',
                        'url'           => Backend::url('bedard/shop/categories'),
                        'permissions'   => ['bedard.shop.categories.*'],
                    ],
                    'discounts' => [
                        'label'         => 'bedard.shop::lang.discounts.plural',
                        'icon'          => 'icon-clock-o',
                        'url'           => Backend::url('bedard/shop/discounts'),
                        'permissions'   => ['bedard.shop.discounts.*'],
                    ],
                    'promotions' => [
                        'label'         => 'bedard.shop::lang.promotions.plural',
                        'icon'          => 'icon-star',
                        'url'           => Backend::url('bedard/shop/promotions'),
                        'permissions'   => ['bedard.shop.promotions.*'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return [
            'bedard.shop.api.manage' => [
                'label' => 'bedard.shop::lang.plugin.permissions.api',
                'tab' => 'bedard.shop::lang.plugin.details.name',
            ],
            'bedard.shop.carts.manage' => [
                'label' => 'bedard.shop::lang.plugin.permissions.carts',
                'tab' => 'bedard.shop::lang.plugin.details.name',
            ],
            'bedard.shop.categories.manage' => [
                'label' => 'bedard.shop::lang.plugin.permissions.categories',
                'tab' => 'bedard.shop::lang.plugin.details.name',
            ],
            'bedard.shop.customers.manage' => [
                'label' => 'bedard.shop::lang.plugin.permissions.customers',
                'tab' => 'bedard.shop::lang.plugin.details.name',
            ],
            'bedard.shop.discounts.manage' => [
                'label' => 'bedard.shop::lang.plugin.permissions.discounts',
                'tab' => 'bedard.shop::lang.plugin.details.name',
            ],
            'bedard.shop.orders.manage' => [
                'label' => 'bedard.shop::lang.plugin.permissions.orders',
                'tab' => 'bedard.shop::lang.plugin.details.name',
            ],
            'bedard.shop.products.manage' => [
                'label' => 'bedard.shop::lang.plugin.permissions.products',
                'tab' => 'bedard.shop::lang.plugin.details.name',
            ],
            'bedard.shop.promotions.manage' => [
                'label' => 'bedard.shop::lang.plugin.permissions.promotions',
                'tab' => 'bedard.shop::lang.plugin.details.name',
            ],
            'bedard.shop.shipping.manage' => [
                'label' => 'bedard.shop::lang.plugin.permissions.shipping',
                'tab' => 'bedard.shop::lang.plugin.details.name',
            ],
        ];
    }

    /**
     * Register settings models.
     *
     * @return array
     */
    public function registerSettings()
    {
        return [
            'api' => [
                'label'         => 'bedard.shop::lang.api.label',
                'description'   => 'bedard.shop::lang.api.description',
                'category'      => 'bedard.shop::lang.plugin.details.name',
                'class'         => 'Bedard\Shop\Models\ApiSettings',
                'permissions'   => ['bedard.shop.api.manage'],
                'icon'          => 'icon-cog',
            ],
            'carts' => [
                'label'         => 'bedard.shop::lang.carts.label',
                'description'   => 'bedard.shop::lang.carts.description',
                'category'      => 'bedard.shop::lang.plugin.details.name',
                'class'         => 'Bedard\Shop\Models\CartSettings',
                'permissions'   => ['bedard.shop.carts.manage'],
                'icon'          => 'icon-shopping-cart',
            ],
            'shipping' => [
                'label'         => 'bedard.shop::lang.shipping.label',
                'description'   => 'bedard.shop::lang.shipping.description',
                'category'      => 'bedard.shop::lang.plugin.details.name',
                'class'         => 'Bedard\Shop\Models\ShippingSettings',
                'permissions'   => ['bedard.shop.shipping.manage'],
                'icon'          => 'icon-truck',
            ],
        ];
    }

    /**
     * Register shipping drivers.
     *
     * @return array
     */
    public function registerShippingDrivers()
    {
        return [
            'Bedard\Shop\Drivers\NoShippingDriver',
        ];
    }
}
