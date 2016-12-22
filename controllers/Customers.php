<?php namespace Bedard\Shop\Controllers;

use BackendMenu;
use Bedard\Shop\Classes\BackendController;

/**
 * Customers Back-end Controller.
 */
class Customers extends BackendController
{
    public $bodyClass = 'compact-container';

    public $formConfig = 'config_form.yaml';

    public $listConfig = 'config_list.yaml';

    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Owl.Behaviors.ListDelete.Behavior',
    ];

    public $registerPermissions = [
        'bedard.shop.customers.manage',
    ];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Bedard.Shop', 'shop', 'customers');
        $this->addJs('/plugins/bedard/shop/assets/dist/customers.js');
    }
}
