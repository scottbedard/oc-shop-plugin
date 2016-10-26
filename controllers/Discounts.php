<?php namespace Bedard\Shop\Controllers;

use BackendMenu;
use Bedard\Shop\Classes\Controller;

/**
 * Discounts Back-end Controller.
 */
class Discounts extends Controller
{
    public $formConfig = 'config_form.yaml';

    public $listConfig = 'config_list.yaml';

    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Owl.Behaviors.ListDelete.Behavior',
    ];

    public $registerPermissions = [
        'bedard.shop.discounts.manage',
    ];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Bedard.Shop', 'shop', 'discounts');
        $this->addJs('/plugins/bedard/shop/assets/dist/discounts.js');
    }

    /**
     * Extend the list query.
     *
     * @param  \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function listExtendQuery($query)
    {
        $query->selectStatus();
    }
}
