<?php namespace Bedard\Shop\Tests\Unit\Models;

use Bedard\Shop\Classes\Factory;
use Bedard\Shop\Models\Category;
use Bedard\Shop\Models\Product;
use PluginTestCase;

class CategoryTest extends PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

    public function test_categoriess_can_belong_to_products()
    {
        $category = Factory::create(new Category);
        $product = Factory::create(new Product);
        $category->products()->attach($product);
        $this->assertEquals($product->id, $category->products()->find($product->id)->id);
    }

    public function test_getting_parent_ids_from_a_category_id()
    {
        $parent = Factory::create(new Category);
        $child = Factory::create(new Category, ['parent_id' => $parent->id]);
        $grandchild = Factory::create(new Category, ['parent_id' => $child->id]);

        $categories = Category::all();
        $this->assertEquals([], Category::getParentIds($parent->id, $categories));
        $this->assertEquals([$parent->id], Category::getParentIds($child->id, $categories));
        $this->assertEquals([$child->id, $parent->id], Category::getParentIds($grandchild->id, $categories));
    }
}
