<?php namespace Bedard\Shop\Tests\Unit\Models;

use Exception;
use PluginTestCase;
use Bedard\Shop\Models\Option;
use Bedard\Shop\Classes\Factory;

class OptionTest extends PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

    public function test_option_values_must_be_unique()
    {
        try {
            $option = Factory::create(new Option, [
                'value_data' => [
                    ['_key' => 1, 'id' => null, 'name' => 'a', 'sort_order' => 0],
                    ['_key' => 2, 'id' => null, 'name' => 'a', 'sort_order' => 1],
                ],
            ]);

            $this->fail('Expected an exception to be thrown, but none was');
        } catch (Exception $e) {
            $this->assertEquals('bedard.shop::lang.options.form.values_unique', $e->getMessage());
        }
    }

    public function test_saving_option_with_no_values_throws_error()
    {
        try {
            $option = Factory::create(new Option);
            $option->value_data = [];
            $option->save();
            $this->fail('Expected an exception to be thrown, but none was');
        } catch (Exception $e) {
            $this->assertEquals('bedard.shop::lang.options.form.values_required', $e->getMessage());
        }
    }

    public function test_saving_related_values()
    {
        $option = Factory::create(new Option, [
            'value_data' => [
                ['_key' => 1, 'id' => null, 'name' => 'a', 'sort_order' => 0],
                ['_key' => 2, 'id' => null, 'name' => 'b', 'sort_order' => 1],
            ],
        ]);

        $this->assertEquals(2, $option->values()->count());
    }
}