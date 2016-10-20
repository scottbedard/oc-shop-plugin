<?php namespace Bedard\Shop\Tests\Models;

use Bedard\Shop\Models\Category;
use Bedard\Shop\Tests\Factory;

class CategoryTest extends \PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

    public function test_category_name_is_required()
    {
        $this->setExpectedException(\October\Rain\Database\ModelException::class);
        Factory::fill(new Category, null, ['name'])->validate();
    }

    public function test_category_getChildIds()
    {
        $grandparent = Factory::create(new Category);
        $parent = Factory::create(new Category, ['parent_id' => $grandparent->id]);
        $child = Factory::create(new Category, ['parent_id' => $parent->id]);
        $grandchild = Factory::create(new Category, ['parent_id' => $child->id]);
        $orphan = Factory::create(new Category, ['parent_id' => 0]);

        $categories = Category::all();
        $this->assertEquals([2, 3, 4], Category::getChildIds($categories, $grandparent));
        $this->assertEquals([3, 4], Category::getChildIds($categories, $parent));
        $this->assertEquals([4], Category::getChildIds($categories, $child));
        $this->assertEquals([], Category::getChildIds($categories, $orphan));
    }

    public function test_category_scopeIsChildOf_and_scopeIsNotChildOf() {
        $parent = Factory::create(new Category);
        $child = Factory::create(new Category, ['parent_id' => $parent->id]);
        $grandchild = Factory::create(new Category, ['parent_id' => $child->id]);

        $children = Category::isChildOf($parent)->get();
        $this->assertEquals([$child->id, $grandchild->id], $children->lists('id'));

        $notChildren = Category::isNotChildOf($child)->get();
        $this->assertEquals([$parent->id, $child->id], $notChildren->lists('id'));
    }

    public function test_category_slug_is_required()
    {
        $this->setExpectedException(\October\Rain\Database\ModelException::class);
        Factory::fill(new Category, null, ['slug'])->validate();
    }

    public function test_category_slug_must_be_unique()
    {
        $this->setExpectedException(\October\Rain\Database\ModelException::class);
        $category = Factory::create(new Category);
        Factory::create(new Category, ['slug' => $category->slug]);
    }
}
