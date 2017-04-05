<?php namespace Bedard\Shop\Controllers;

use BackendMenu;
use Bedard\Shop\Classes\BackendController;
use Bedard\Shop\Models\Product;
use Queue;

/**
 * Categories Back-end Controller.
 */
class Categories extends BackendController
{
    public $formConfig = 'config_form.yaml';

    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Backend.Behaviors.ReorderController',
        'Owl.Behaviors.ListDelete.Behavior',
    ];

    public $listConfig = 'config_list.yaml';

    public $reorderConfig = 'config_reorder.yaml';

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

    /**
     * When categories are reordered, sync all inherited products.
     *
     * @return void
     */
    public function onReorder()
    {
        // call the original reorder method
        $this->asExtension('ReorderController')->onReorder();

        // queue up a job to sync all products with their inherited categories
        Queue::push(function ($job) {
            Product::syncAllCategories();
            $job->delete();
        });
    }
}
