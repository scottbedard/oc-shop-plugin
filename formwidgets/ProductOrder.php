<?php namespace Bedard\Shop\FormWidgets;

use Backend\Classes\FormWidgetBase;

/**
 * ProductOrder Form Widget.
 */
class ProductOrder extends FormWidgetBase
{
    /**
     * {@inheritdoc}
     */
    protected $defaultAlias = 'bedard_shop_product_order';

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $this->prepareVars();

        return $this->makePartial('productorder');
    }

    /**
     * Prepares the form widget view data.
     */
    public function prepareVars()
    {
        $this->model->load('products');

        $this->vars['products'] = $this->model->products;
    }

    /**
     * {@inheritdoc}
     */
    public function getSaveValue($value)
    {
        return $value;
    }
}
