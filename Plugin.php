<?php namespace Bedard\Shop;

use Backend;
use System\Classes\PluginBase;

/**
 * Shop Plugin Information File.
 */
class Plugin extends PluginBase
{
    /**
     * @var array Plugin dependencies
     */
    public $require = [
        'RainLab.User',
    ];

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
        $this->registerConsoleCommand('shop.abandon', 'Bedard\Shop\Console\Abandon');
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
                'label'       => 'bedard.shop::lang.plugin.name',
                'order'       => 500,
                'permissions' => ['bedard.shop.*'],
                'url'         => Backend::url('bedard/shop/carts'),
                'sideMenu' => [
                    'carts' => [
                        'icon'          => 'icon-shopping-cart',
                        'label'         => 'bedard.shop::lang.carts.plural',
                        'permissions'   => ['bedard.shop.carts.*'],
                        'url'           => Backend::url('bedard/shop/carts'),
                    ],
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
                    'settings' => [
                        'icon'          => 'icon-cog',
                        'label'         => 'bedard.shop::lang.settings.plural',
                        'permissions'   => ['bedard.shop.settings.*'],
                        'url'           => Backend::url('system/settings/update/bedard/shop/general'),
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
            'bedard.shop.carts.manage' => [
                'label' => 'bedard.shop::lang.plugin.permissions.carts',
                'tab' => 'bedard.shop::lang.plugin.name',
            ],
            'bedard.shop.categories.manage' => [
                'label' => 'bedard.shop::lang.plugin.permissions.categories',
                'tab' => 'bedard.shop::lang.plugin.name',
            ],
            'bedard.shop.products.manage' => [
                'label' => 'bedard.shop::lang.plugin.permissions.products',
                'tab' => 'bedard.shop::lang.plugin.name',
            ],
            'bedard.shop.settings.manage' => [
                'label' => 'bedard.shop::lang.plugin.permissions.settings',
                'tab' => 'bedard.shop::lang.plugin.name',
            ],
            'bedard.shop.statuses.manage' => [
                'label' => 'bedard.shop::lang.plugin.permissions.statuses',
                'tab' => 'bedard.shop::lang.plugin.name',
            ],
        ];
    }

    /**
     * Register scheduled tasks.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule $schedule
     * @return void
     */
    public function registerSchedule($schedule)
    {
        $schedule->command('shop:abandon')->everyTenMinutes();
    }

    /**
     * Register settings models.
     *
     * @return array
     */
    public function registerSettings()
    {
        return [
            'general' => [
                'category'      => 'bedard.shop::lang.plugin.name',
                'class'         => 'Bedard\Shop\Models\Settings',
                'description'   => 'bedard.shop::lang.settings.title',
                'icon'          => 'icon-cog',
                'label'         => 'bedard.shop::lang.settings.label',
                'order'         => 100,
                'permissions'   => ['bedard.shop.settings.manage'],
            ],
            'api' => [
                'category'      => 'bedard.shop::lang.plugin.name',
                'class'         => 'Bedard\Shop\Models\ApiSettings',
                'description'   => 'bedard.shop::lang.api.title',
                'icon'          => 'icon-code',
                'label'         => 'bedard.shop::lang.api.label',
                'order'         => 200,
                'permissions'   => ['bedard.shop.settings.manage'],
            ],
            'payment' => [
                'category'      => 'bedard.shop::lang.plugin.name',
                'class'         => 'Bedard\Shop\Models\PaymentDrivers',
                'description'   => 'bedard.shop::lang.payment.title',
                'icon'          => 'icon-money',
                'label'         => 'bedard.shop::lang.payment.label',
                'order'         => 300,
                'permissions'   => ['bedard.shop.settings.manage'],
            ],
        ];
    }

    /**
     * Register drivers.
     *
     * @return array
     */
    public function registerShopDrivers()
    {
        return [
            'nopayment' => [
                'class'     => 'Bedard\Shop\Drivers\NoPayment',
                'name'      => 'bedard.shop::lang.drivers.nopayment.name',
                'thumbnail' => null,
                'type'      => 'payment',
            ],
        ];
    }
}
