<?php namespace Bedard\Shop\FormWidgets;

use Backend\Classes\FormWidgetBase;

/**
 * CartHistory Form Widget
 */
class CartHistory extends FormWidgetBase
{
    /**
     * @inheritDoc
     */
    protected $defaultAlias = 'bedard_shop_cart_history';

    /**
     * @inheritDoc
     */
    public function render()
    {
        $this->prepareVars();
        return $this->makePartial('carthistory');
    }

    /**
     * Prepares the form widget view data
     */
    public function prepareVars()
    {
        $this->vars['name'] = $this->formField->getName();
        $this->vars['value'] = $this->getLoadValue();
        $this->vars['model'] = $this->model;
    }

    /**
     * @inheritDoc
     */
    public function loadAssets()
    {
        $this->addJs('/plugins/bedard/shop/assets/dist/cart_history.min.js', 'Bedard.Shop');
    }

    /**
     * @inheritDoc
     */
    public function getSaveValue($value)
    {
        return $value;
    }
}
