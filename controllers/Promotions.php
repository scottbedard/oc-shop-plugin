<?php namespace Bedard\Shop\Controllers;

use BackendMenu;
use Bedard\Shop\Classes\BackendController;

/**
 * Promotions Back-end Controller
 */
class Promotions extends BackendController
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';

    public $listConfig = 'config_list.yaml';

    public $registerPermissions = [
        'bedard.shop.promotions.manage',
    ];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Bedard.Shop', 'shop', 'promotions');
        $this->addJs('/plugins/bedard/shop/assets/dist/promotions.js');
    }
}
