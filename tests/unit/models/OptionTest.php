<?php namespace Bedard\Shop\Tests\Unit\Models;

use Bedard\Shop\Classes\Factory;
use Bedard\Shop\Models\Option;
use Exception;
use PluginTestCase;

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

    public function test_saving_related_option_values()
    {
        $option = Factory::create(new Option, [
            'value_data' => [
                ['_key' => 1, 'id' => null, 'name' => 'a', 'sort_order' => 0],
                ['_key' => 2, 'id' => null, 'name' => 'b', 'sort_order' => 1],
            ],
        ]);

        $this->assertEquals(2, $option->values()->count());
    }

    public function test_deleting_a_value()
    {
        $option = Factory::create(new Option, [
            'value_data' => [
                ['_key' => 1, 'id' => null, 'name' => 'a', 'sort_order' => 0],
                ['_key' => 2, 'id' => null, 'name' => 'b', 'sort_order' => 1],
            ],
        ]);

        $option->value_data = [
            ['_deleted' => true, 'id' => 1, 'name' => 'a', 'sort_order' => 0],
            ['id' => 2, 'name' => 'b', 'sort_order' => 1],
        ];

        $option->save();
        $this->assertEquals(1, $option->values()->count());
        $this->assertEquals(0, $option->values()->where('name', 'a')->count());
        $this->assertEquals(1, $option->values()->where('name', 'b')->count());
    }

    public function test_options_cant_be_created_with_only_deleted_values()
    {
        try {
            $option = Factory::create(new Option, [
                'value_data' => [
                    ['_deleted' => true, 'id' => null, 'name' => 'a', 'sort_order' => 0],
                ],
            ]);

            $this->fail('Expected an exception to be thrown, but none was');
        } catch (Exception $e) {
            $this->assertEquals('bedard.shop::lang.options.form.values_required', $e->getMessage());
        }
    }
}
