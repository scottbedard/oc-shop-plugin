<?php namespace Bedard\Shop\FormWidgets;

use Backend\Classes\FormWidgetBase;
use Bedard\Shop\Models\Status;

/**
 * StatusSelector Form Widget.
 */
class StatusSelector extends FormWidgetBase
{
    /**
     * {@inheritdoc}
     */
    protected $defaultAlias = 'bedard_shop_status_selector';

    /**
     * {@inheritdoc}
     */
    public function render()
    {
        $this->prepareVars();

        return $this->makePartial('statusselector');
    }

    /**
     * Prepares the form widget view data.
     */
    public function prepareVars()
    {
        $this->vars['name'] = $this->formField->getName();
        $this->vars['value'] = $this->getLoadValue();
        $this->vars['model'] = $this->model;

        $this->vars['statuses'] = Status::all();
    }

    /**
     * {@inheritdoc}
     */
    public function getSaveValue($value)
    {
        return $value;
    }
}
