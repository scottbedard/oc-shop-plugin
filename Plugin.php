<?php namespace Bedard\Shop;

use Backend;
use System\Classes\PluginBase;

/**
 * Shop Plugin Information File
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
                'permissions' => ['bedard.shop.products.*'],
                'url'         => Backend::url('bedard/shop/products'),
                'sideMenu' => [
                    'products' => [
                        'label'         => 'bedard.shop::lang.products.controller',
                        'icon'          => 'icon-cubes',
                        'url'           => Backend::url('bedard/shop/products'),
                        'permissions'   => ['bedard.shop.products.*'],
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
            'bedard.shop.products.manage' => [
                'label' => 'bedard.shop::lang.plugin.permissions.products',
                'tab' => 'bedard.shop::lang.plugin.details.name',
            ],
        ];
    }

}
