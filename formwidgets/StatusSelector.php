<?php namespace Bedard\Shop\FormWidgets;

use Backend\Classes\FormWidgetBase;
use Bedard\Shop\Models\Status;

/**
 * StatusSelector Form Widget
 */
class StatusSelector extends FormWidgetBase
{
    /**
     * @inheritDoc
     */
    protected $defaultAlias = 'bedard_shop_status_selector';

    /**
     * @inheritDoc
     */
    public function init()
    {
    }

    /**
     * @inheritDoc
     */
    public function render()
    {
        $this->prepareVars();
        return $this->makePartial('statusselector');
    }

    /**
     * Prepares the form widget view data
     */
    public function prepareVars()
    {
        $this->vars['name'] = $this->formField->getName();
        $this->vars['value'] = $this->getLoadValue();
        $this->vars['model'] = $this->model;

        $this->vars['statuses'] = Status::all();
    }

    /**
     * @inheritDoc
     */
    public function loadAssets()
    {
        $this->addJs('/plugins/bedard/shop/assets/dist/vendor.min.js', 'Bedard.Shop');
        $this->addJs('/plugins/bedard/shop/assets/dist/status_selector.min.js', 'Bedard.Shop');
    }

    /**
     * @inheritDoc
     */
    public function getSaveValue($value)
    {
        return $value;
    }
}
