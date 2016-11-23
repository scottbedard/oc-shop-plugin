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
            'bedard.shop.categories.manage' => [
                'label' => 'bedard.shop::lang.plugin.permissions.categories',
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
        ];
    }
}
