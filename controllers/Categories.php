<?php namespace Bedard\Shop\Controllers;

use BackendMenu;
use Bedard\Shop\Classes\BackendController;

/**
 * Categories Back-end Controller.
 */
class Categories extends BackendController
{
    public $formConfig = 'config_form.yaml';

    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Owl.Behaviors.ListDelete.Behavior',
    ];

    public $listConfig = 'config_list.yaml';

    public $registerPermissions = [
        'bedard.shop.categories.manage',
    ];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Bedard.Shop', 'shop', 'categories');

        $this->addJs('/plugins/bedard/shop/assets/dist/categories.min.js');
    }

    /**
     * Extend the list query.
     *
     * @param  \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function listExtendQuery($query)
    {
        $query->with('products');
    }
}
