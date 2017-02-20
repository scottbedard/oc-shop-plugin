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
            'name'        => 'bedard.shop::lang.plugin.name',
            'description' => 'bedard.shop::lang.plugin.description',
            'author'      => 'Bedard',
            'icon'        => 'icon-shopping-cart',
        ];
    }

    /**
     * Boot method, called right before the request route.
     *
     * @return array
     */
    public function boot()
    {
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return []; // Remove this line to activate

        return [
            'Bedard\Shop\Components\MyComponent' => 'myComponent',
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
                'label'       => 'Shop',
                'order'       => 500,
                'permissions' => ['bedard.shop.*'],
                'url'         => Backend::url('bedard/shop/products'),
                'sideMenu' => [
                    'products' => [
                        'icon'          => 'icon-cubes',
                        'label'         => 'bedard.shop::lang.products.plural',
                        'permissions'   => ['bedard.shop.products.*'],
                        'url'           => Backend::url('bedard/shop/products'),
                    ],
                    'categories' => [
                        'icon'          => 'icon-folder-o',
                        'label'         => 'bedard.shop::lang.categories.plural',
                        'permissions'   => ['bedard.shop.categories.*'],
                        'url'           => Backend::url('bedard/shop/categories'),
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
        return []; // Remove this line to activate

        return [
            'bedard.shop.some_permission' => [
                'tab' => 'Shop',
                'label' => 'Some permission',
            ],
        ];
    }
}
