<?php namespace Bedard\Shop\Controllers;

use BackendMenu;
use Bedard\Shop\Classes\BackendController;

/**
 * Statuses Back-end Controller
 */
 class Statuses extends BackendController
 {
    public $formConfig = 'config_form.yaml';

    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Owl.Behaviors.ListDelete.Behavior',
    ];

    public $listConfig = 'config_list.yaml';

    public $registerPermissions = [
        'bedard.shop.statuses.manage',
    ];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Bedard.Shop', 'shop', 'statuses');
    }
}
