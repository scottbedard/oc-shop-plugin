<?php namespace Bedard\Shop\Controllers;

use BackendMenu;
use Bedard\Shop\Classes\Controller;
use Bedard\Shop\Models\Category;
use Bedard\Shop\Models\Product;
use Exception;
use Lang;
use Log;
use Response;

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
        'Owl.Behaviors.ListDelete.Behavior',
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

    /**
     * Launch the reorder popup.
     *
     * @return string
     */
    public function onReorderClicked()
    {
        $categories = Category::all();

        return $this->makePartial('$/bedard/shop/controllers/categories/_reorder.htm', [
            'categories' => $categories,
        ]);
    }

    /**
     * Reorder the categories.
     *
     * @return Response
     */
    public function reorder()
    {
        try {
            Category::updateMany(input('categories'));
            Product::syncAllInheritedCategories();
            $success = Lang::get('bedard.shop::lang.categories.list.reorder_success');

            return Response::make($success);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            $failure = Lang::get('bedard.shop::lang.categories.list.reorder_failure');

            return Response::make($failure, 500);
        }
    }
}
