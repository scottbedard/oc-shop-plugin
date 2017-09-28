<?php namespace Bedard\Shop\Tests\Backend\Models;

use Bedard\Shop\Classes\Factory;
use Bedard\Shop\Models\Category;
use Bedard\Shop\Models\Product;
use Bedard\Shop\Tests\Backend\ShopTestCase;

class CategoryTest extends ShopTestCase
{
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

    public function test_categories_inherit_products_from_their_children()
    {
        $parent = Factory::create(new Category);
        $child = Factory::create(new Category, ['parent_id' => $parent->id]);
        $grandchild = Factory::create(new Category, ['parent_id' => $child->id]);

        $product1 = Factory::create(new Product);
        $product2 = Factory::create(new Product);
        $product1->categories()->attach($child);
        $product2->categories()->attach($grandchild);

        Product::syncAllCategories();

        $this->assertEquals(2, $parent->products()->count());
        $this->assertEquals(2, $child->products()->count());
        $this->assertEquals(1, $grandchild->products()->count());
    }
}
