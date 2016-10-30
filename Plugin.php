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
            'Bedard\Shop\FormWidgets\OptionsInventories' => [
                'label' => 'Options Inventories',
                'code'  => 'options-inventories',
            ],
            'Owl\FormWidgets\Knob\Widget' => [
                'label' => 'Knob',
                'code'  => 'owl-knob',
            ],
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
                'url'         => Backend::url('bedard/shop/products'),
                'sideMenu' => [
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
            'bedard.shop.categories.manage' => [
                'label' => 'bedard.shop::lang.plugin.permissions.categories',
                'tab' => 'bedard.shop::lang.plugin.details.name',
            ],
            'bedard.shop.discounts.manage' => [
                'label' => 'bedard.shop::lang.plugin.permissions.discounts',
                'tab' => 'bedard.shop::lang.plugin.details.name',
            ],
            'bedard.shop.products.manage' => [
                'label' => 'bedard.shop::lang.plugin.permissions.products',
                'tab' => 'bedard.shop::lang.plugin.details.name',
            ],
        ];
    }
}
