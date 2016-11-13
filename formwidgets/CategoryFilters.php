<?php namespace Bedard\Shop\FormWidgets;

use Backend\Classes\FormWidgetBase;

/**
 * CategoryFilters Form Widget
 */
class CategoryFilters extends FormWidgetBase
{

    /**
     * {@inheritDoc}
     */
    protected $defaultAlias = 'bedard_shop_category_filters';

    /**
     * {@inheritDoc}
     */
    public function render()
    {
        $this->prepareVars();
        return $this->makePartial('categoryfilters');
    }

    /**
     * Prepares the form widget view data
     */
    public function prepareVars()
    {
        $this->model->load('filters');
    }

    /**
     * {@inheritDoc}
     */
    public function getSaveValue($value)
    {
        $data = json_decode(input('categoryFilters'), true);
        
        return $data;
    }

}
