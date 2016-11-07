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
        if ($this->model->exists) {
            $this->vars['options'] = $this->model
                ->options()
                ->with('values')
                ->get()
                ->toArray();

            $inventories = $this->model
                ->inventories()
                ->with('optionValues')
                ->get();

            foreach ($inventories as &$inventory) {
                $inventory['valueIds'] = $inventory->optionValues->lists('id');
            }

            $this->vars['inventories'] = $inventories->toArray();
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
        $data = json_decode(input('optionsInventories'), true);

        return $data;
    }
}
