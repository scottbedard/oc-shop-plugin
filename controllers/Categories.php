<?php namespace Bedard\Shop\Controllers;

use BackendMenu;
use Bedard\Shop\Classes\Controller;
use Bedard\Shop\Models\Category;

/**
 * Categories Back-end Controller.
 */
class Categories extends Controller
{
    public $formConfig = 'config_form.yaml';

    public $listConfig = 'config_list.yaml';

    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
    ];

    public $registerPermissions = [
        'bedard.shop.categories.manage',
    ];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Bedard.Shop', 'shop', 'categories');
        $this->addJs('/plugins/bedard/shop/assets/dist/categories.js');
    }

    public function onReorder()
    {
        $categories = Category::all();

        return $this->makePartial('$/bedard/shop/controllers/categories/_reorder.htm', [
            'categories' => $categories,
        ]);
    }
}
