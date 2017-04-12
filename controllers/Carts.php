<?php namespace Bedard\Shop\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

/**
 * Carts Back-end Controller.
 */
class Carts extends Controller
{
    public $bodyClass = 'compact-container';

    public $formConfig = 'config_form.yaml';

    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Owl.Behaviors.ListDelete.Behavior',
    ];

    public $listConfig = 'config_list.yaml';

    public $registerPermissions = [
        'bedard.shop.carts.manage',
    ];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Bedard.Shop', 'shop', 'carts');
    }
}
