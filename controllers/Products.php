<?php namespace Bedard\Shop\Controllers;

use BackendMenu;
use Backend\Classes\Controller;

/**
 * Products Back-end Controller.
 */
class Products extends Controller
{
    public $formConfig = 'config_form.yaml';

    public $listConfig = 'config_list.yaml';

    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
    ];

    public $registerPermissions = [
        'bedard.shop.products.manage',
    ];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Bedard.Shop', 'shop', 'products');
    }
}
