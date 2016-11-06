<?php namespace Bedard\Shop\Controllers;

use BackendMenu;
use Bedard\Shop\Classes\BackendController;
use Bedard\Shop\Models\Category;
use Bedard\Shop\Models\Product;
use Exception;
use Flash;
use Lang;
use Log;
use Response;

/**
 * Categories Back-end Controller.
 */
class Categories extends BackendController
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
     * After list delete.
     *
     * @return void
     */
    public function afterListDelete()
    {
        Product::syncAllInheritedCategories();
        Flash::success(Lang::get('backend::lang.list.delete_selected_success'));
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
     * Override the default list delete behavior.
     *
     * @param  \Bedard\Shop\Models\Category $category
     * @return void
     */
    public function overrideListDelete(Category $category)
    {
        $category->dontSyncAfterDelete = true;
        $category->delete();
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
