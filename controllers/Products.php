<?php namespace Bedard\Shop\Controllers;

use BackendMenu;
use Bedard\Shop\Classes\BackendController;

/**
 * Products Back-end Controller.
 */
class Products extends BackendController
{
    public $formConfig = 'config_form.yaml';

    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController',
        'Owl.Behaviors.ListDelete.Behavior',
    ];

    public $listConfig = 'config_list.yaml';

    public $registerPermissions = [
        'bedard.shop.products.manage',
    ];

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Bedard.Shop', 'shop', 'products');

        $this->addJs('/plugins/bedard/shop/assets/dist/products.min.js');
    }

    /**
     * Join a subquery containing the product's current inventory count to the
     * list. In order to avoid a PDO binding mismatch, attaching subqueried
     * joins must be done before attaching anything else to the builder.
     *
     * @param  \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function listExtendQueryBefore($query)
    {
        $query->joinInventoryCount();
    }

    /**
     * Attaches our column select statements to the query. Unfortunately, this
     * can't be done from listExtendQueryBefore. Select statements that are
     * added before processing the query will be removed by the behavior.
     *
     * @param  \Illuminate\Database\Query\Builder $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function listExtendQuery($query)
    {
        $query
            ->select('bedard_shop_products.*', 'inventory_count')
            ->selectStatus();
    }
}
