<?php namespace Bedard\Shop\Tests\Unit\Models;

use Bedard\Shop\Classes\Factory;
use Bedard\Shop\Models\Category;
use Bedard\Shop\Models\Inventory;
use Bedard\Shop\Models\Option;
use Bedard\Shop\Models\Product;
use DB;
use PluginTestCase;

class ProductTest extends PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

    public function test_getting_formatted_price()
    {
        $product = new Product;
        $product->base_price = 1.2;
        $this->assertEquals('1.20', $product->formattedPrice());
    }

    public function test_products_can_belong_to_categories()
    {
        $category = Factory::create(new Category);
        $product = Factory::create(new Product);
        $product->categories()->attach($category);
        $this->assertEquals($category->id, $product->categories()->find($category->id)->id);
    }

    public function test_setting_plain_description()
    {
        $product = Factory::create(new Product, ['description_html' => '<b>Hello</b>']);
        $this->assertEquals('Hello', $product->description_plain);
    }

    public function test_selecting_status()
    {
        $disabled = Factory::create(new Product, ['is_enabled' => false]);
        $enabled = Factory::create(new Product, ['is_enabled' => true]);
        $products = Product::selectStatus()->get();
        $this->assertEquals(0, $products->find($disabled->id)->status);
        $this->assertEquals(1, $products->find($enabled->id)->status);
    }

    public function test_saving_options()
    {
        $option = Factory::create(new Option);
        $option->load('values');
        $optionData = $option->toArray();
        $optionData['value_data'] = $optionData['values'];
        unset($optionData['values']);

        $product = Factory::create(new Product, [
            'options_inventories' => json_encode([
                'inventories' => [],
                'options' => [$optionData],
            ]),
        ]);

        $this->assertEquals(1, $product->options()->count());
        $this->assertEquals($option->id, $product->options()->first()->id);
    }

    public function test_deleting_options()
    {
        $option = Factory::create(new Option);
        $option->load('values');
        $optionData = $option->toArray();
        $optionData['value_data'] = $optionData['values'];
        unset($optionData['values']);

        $product = Factory::create(new Product, [
            'options_inventories' => json_encode([
                'inventories' => [],
                'options' => [$optionData],
            ]),
        ]);

        $product->options_inventories = json_encode([
            'inventories' => [],
            'options' => [
                [
                    '_deleted' => true,
                    'id' => $option->id,
                    'name' => $option->name,
                    'value_data' => [['id' => null, 'name' => 'a']],
                ],
            ],
        ]);

        $product->save();
        $this->assertEquals(0, $product->options()->count());
    }

    public function test_saving_default_inventory()
    {
        $inventory = Factory::create(new Inventory);
        $inventoryData = $inventory->toArray();
        $inventoryData['value_ids'] = [];

        $product = Factory::create(new Product, [
            'options_inventories' => json_encode([
                'inventories' => [$inventoryData],
                'options' => [],
            ]),
        ]);

        $this->assertEquals(1, $product->inventories()->count());
        $this->assertEquals($inventory->id, $product->inventories()->first()->id);
    }

    public function test_is_enabled_scope()
    {
        $enabled = Factory::create(new Product, ['is_enabled' => true]);
        $disabled = Factory::create(new Product, ['is_enabled' => false]);

        $query = Product::isEnabled();
        $this->assertEquals(1, $query->count());
        $this->assertEquals($enabled->id, $query->first()->id);
    }

    public function test_product_saves_its_ancestor_categories()
    {
        $product = Factory::create(new Product);
        $grandparent = Factory::create(new Category);
        $parent = Factory::create(new Category, ['parent_id' => $grandparent->id]);
        $child = Factory::create(new Category, ['parent_id' => $parent->id]);

        $product->categories_field = [$child->id];
        $product->save();

        $direct = DB::table('bedard_shop_category_product')
            ->select('category_id')
            ->whereIsInherited(false)
            ->lists('category_id');

        $inherited = DB::table('bedard_shop_category_product')
            ->select('category_id')
            ->whereIsInherited(true)
            ->lists('category_id');

        $this->assertEquals([$child->id], $direct);
        $this->assertEquals([$grandparent->id, $parent->id], $inherited);
    }

    public function test_adding_a_parent_category()
    {
        $product = Factory::create(new Product);
        $parent = Factory::create(new Category);
        $child = Factory::create(new Category, ['parent_id' => $parent->id]);

        $product->categories_field = [$parent->id];
        $product->save();

        $direct = DB::table('bedard_shop_category_product')
            ->select('category_id')
            ->whereIsInherited(false)
            ->lists('category_id');

        $inherited = DB::table('bedard_shop_category_product')
            ->select('category_id')
            ->whereIsInherited(true)
            ->lists('category_id');

        $this->assertEquals([$parent->id], $direct);
        $this->assertEquals([], $inherited);
    }

    public function test_moving_a_product_from_child_to_parent()
    {
        $product = Factory::create(new Product);
        $parent = Factory::create(new Category);
        $child = Factory::create(new Category, ['parent_id' => $parent->id]);

        $product->categories_field = [$child->id];
        $product->save();

        $product->categories_field = [$parent->id];
        $product->save();

        $direct = DB::table('bedard_shop_category_product')
            ->select('category_id')
            ->whereIsInherited(false)
            ->lists('category_id');

        $inherited = DB::table('bedard_shop_category_product')
            ->select('category_id')
            ->whereIsInherited(true)
            ->lists('category_id');

        $this->assertEquals([$parent->id], $direct);
        $this->assertEquals([], $inherited);
    }

    public function test_promoting_from_inherited_to_direct()
    {
        $product = Factory::create(new Product);
        $parent = Factory::create(new Category);
        $child = Factory::create(new Category, ['parent_id' => $parent->id]);

        $product->categories_field = [$child->id];
        $product->save();

        $product->categories_field = [$child->id, $parent->id];
        $product->save();

        $direct = DB::table('bedard_shop_category_product')
            ->select('category_id')
            ->whereIsInherited(false)
            ->lists('category_id');

        $inherited = DB::table('bedard_shop_category_product')
            ->select('category_id')
            ->whereIsInherited(true)
            ->lists('category_id');

        $this->assertEquals([$child->id, $parent->id], $direct);
        $this->assertEquals([], $inherited);
    }
}
