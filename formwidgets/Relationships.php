<?php namespace Bedard\Shop\FormWidgets;

use Backend\Classes\FormWidgetBase;

/**
 * Relationships Form Widget
 */
class Relationships extends FormWidgetBase
{
    /**
     * @inheritDoc
     */
    protected $defaultAlias = 'bedard_shop_relationships';

    /**
     * @inheritDoc
     */
    public function render()
    {
        $this->prepareVars();
        return $this->makePartial('relationships');
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
        $this->addJs('/plugins/bedard/shop/assets/dist/relationships.min.js', 'Bedard.Shop');
    }

    /**
     * @inheritDoc
     */
    public function getSaveValue($value)
    {
        $value = json_decode($value, true);
        $value['relationships'] = array_filter($value['relationships']);

        return $value;
    }
}
