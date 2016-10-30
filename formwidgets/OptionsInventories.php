<?php namespace Bedard\Shop\FormWidgets;

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
        $this->vars['name'] = $this->formField->getName();
        $this->vars['value'] = $this->getLoadValue();
        $this->vars['model'] = $this->model;

        if ($this->model->exists) {
            $this->vars['options'] = $this->model->options()->get()->toArray();
            $this->vars['inventories'] = $this->model->inventories()->get()->toArray();
        } else {
            $this->vars['options'] = [];
            $this->vars['inventories'] = [];
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSaveValue($value)
    {
        return $value;
    }
}
