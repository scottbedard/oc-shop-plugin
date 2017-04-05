<?php namespace Bedard\Shop\FormWidgets;

use Backend\Classes\FormWidgetBase;

/**
 * Relationships Form Widget.
 */
class Relationships extends FormWidgetBase
{
    /**
     * {@inheritdoc}
     */
    protected $defaultAlias = 'bedard_shop_relationships';

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $this->prepareVars();

        return $this->makePartial('relationships');
    }

    /**
     * Prepares the form widget view data.
     */
    public function prepareVars()
    {
        $this->vars['name'] = $this->formField->getName();
        $this->vars['value'] = $this->getLoadValue();
        $this->vars['model'] = $this->model;
    }

    /**
     * {@inheritdoc}
     */
    public function loadAssets()
    {
        $this->addJs('/plugins/bedard/shop/assets/dist/vendor.min.js', 'Bedard.Shop');
        $this->addJs('/plugins/bedard/shop/assets/dist/relationships.min.js', 'Bedard.Shop');
    }

    /**
     * {@inheritdoc}
     */
    public function getSaveValue($value)
    {
        $value = json_decode($value, true);
        $value['relationships'] = array_filter($value['relationships']);

        return $value;
    }
}
