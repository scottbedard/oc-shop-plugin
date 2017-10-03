<?php namespace Bedard\Shop\FormWidgets;

use Backend;
use Backend\Classes\FormWidgetBase;

/**
 * Inventory Form Widget.
 */
class Inventory extends FormWidgetBase
{
    /**
     * {@inheritdoc}
     */
    protected $defaultAlias = 'bedard_shop_inventory';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $this->prepareVars();

        return $this->makePartial('inventory');
    }

    /**
     * Prepares the form widget view data.
     */
    public function prepareVars()
    {
        $this->vars['name'] = $this->formField->getName();
        $this->vars['value'] = $this->getLoadValue();
        $this->vars['model'] = $this->model;

        $this->vars['endpoints'] = [
            'createInventory' => Backend::url('bedard/shop/inventories/create'),
            // 'createOption' => Backend::url('bedard/shop/options/create'),
            // 'validateInventory' => Backend::url('bedard/shop/inventories/validate'),
            'validateOption' => Backend::url('bedard/shop/options/validate'),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function loadAssets()
    {
        $this->addJs('/plugins/bedard/shop/assets/dist/inventory.min.js', 'Bedard.Shop');
    }

    /**
     * {@inheritdoc}
     */
    public function getSaveValue($value)
    {
        return $value;
    }
}
