<?php namespace Bedard\Shop\FormWidgets;

use Backend\Classes\FormWidgetBase;

/**
 * OptionsInventories Form Widget
 */
class OptionsInventories extends FormWidgetBase
{
    /**
     * {@inheritDoc}
     */
    protected $defaultAlias = 'bedard_shop_options_inventories';

    /**
     * {@inheritDoc}
     */
    public function init()
    {
    }

    /**
     * {@inheritDoc}
     */
    public function render()
    {
        $this->prepareVars();
        return $this->makePartial('optionsinventories');
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
     * {@inheritDoc}
     */
    public function loadAssets()
    {
        $this->addCss('css/optionsinventories.css', 'Bedard.Shop');
        $this->addJs('js/optionsinventories.js', 'Bedard.Shop');
    }

    /**
     * {@inheritDoc}
     */
    public function getSaveValue($value)
    {
        return $value;
    }
}
