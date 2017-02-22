<?php namespace Bedard\Shop\FormWidgets;

use Backend;
use Backend\Classes\FormWidgetBase;

/**
 * OptionsInventories Form Widget.
 */
class OptionsInventories extends FormWidgetBase
{
    /**
     * {@inheritdoc}
     */
    protected $defaultAlias = 'bedard_shop_options_inventories';

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $this->prepareVars();

        return $this->makePartial('optionsinventories');
    }

    /**
     * Prepares the form widget view data.
     */
    public function prepareVars()
    {
        $this->model->load('options.values');

        $this->vars['name'] = $this->formField->getName();

        $this->vars['endpoints'] = [
            'createOption' => Backend::url('bedard/shop/options/create'),
        ];

        $this->vars['product'] = $this->model;
    }

    /**
     * {@inheritdoc}
     */
    public function loadAssets()
    {
        $this->addJs('/plugins/bedard/shop/assets/dist/options_inventories.min.js', 'Bedard.Shop');
    }

    /**
     * {@inheritdoc}
     */
    public function getSaveValue($value)
    {
        return $value;
    }
}
