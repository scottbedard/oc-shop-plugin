<?php namespace Bedard\Shop\Tests\Unit\Models;

use PluginTestCase;
use Bedard\Shop\Classes\Factory;
use Bedard\Shop\Models\Option;
use Bedard\Shop\Models\OptionValue;
use Exception;

class OptionTest extends PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

    public function test_option_values_must_be_unique()
    {
        $option = Factory::create(new Option);
        $option->value_data = [
            [ '_key' => 1, 'id' => null, 'name' => 'a', 'option_id' => $option->id, 'sort_order' => 0 ],
            [ '_key' => 2, 'id' => null, 'name' => 'a', 'option_id' => $option->id, 'sort_order' => 1 ],
        ];

        try {
            $option->save();
            $this->fail('Expected an exception to be thrown, but none was');
        } catch (Exception $e) {
            $this->assertEquals('bedard.shop::lang.options.form.values_unique', $e->getMessage());
        }
    }

    public function test_saving_related_values()
    {
        $option = Factory::create(new Option);
        $option->value_data = [
            [ '_key' => 1, 'id' => null, 'name' => 'a', 'option_id' => $option->id, 'sort_order' => 0 ],
            [ '_key' => 2, 'id' => null, 'name' => 'b', 'option_id' => $option->id, 'sort_order' => 1 ],
        ];

        $option->save();
        $this->assertEquals(2, $option->values()->count());
    }
}
