<?php namespace Bedard\Shop\Tests\Models;

use Bedard\Shop\Models\Category;
use Bedard\Shop\Tests\Factory;

class CategoryTest extends \PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

    public function test_category_doesnt_show_invalid_parents_in_dropdown()
    {
        $parent = Factory::create(new Category);
        $child = Factory::create(new Category, ['parent_id' => $parent->id]);

        $this->assertEquals([0, $parent->id, $child->id], array_keys(Factory::fill(new Category)->getParentIdOptions()));
        $this->assertEquals([0, $parent->id], array_keys($child->getParentIdOptions()));
        $this->assertEquals([0], array_keys($parent->getParentIdOptions()));
    }

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
        $this->assertEquals([2, 3, 4], Category::getChildIds($grandparent));
        $this->assertEquals([3, 4], Category::getChildIds($parent));
        $this->assertEquals([4], Category::getChildIds($child));
        $this->assertEquals([], Category::getChildIds($orphan));
    }

    public function test_category_scopeIsChildOf_and_scopeIsNotChildOf()
    {
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

    public function test_categories_can_be_reordered_in_bulk()
    {
        $foo = Factory::create(new Category);
        $bar = Factory::create(new Category, ['parent_id' => $foo->id]);
        $baz = Factory::create(new Category, ['parent_id' => $bar->id]);

        $this->assertEquals(0, Category::isChildOf($baz)->count());

        Category::updateMany([
            ['id' => $foo->id, 'parent_id' => $bar->id],
            ['id' => $bar->id, 'parent_id' => $baz->id],
            ['id' => $baz->id, 'parent_id' => null],
        ]);

        $this->assertEquals(2, Category::isChildOf($baz)->count());
    }

    public function test_it_strips_tags_from_description_html_before_save()
    {
        $foo = Factory::create(new Category, ['description_html' => '<strong>Hello</strong>']);
        $this->assertEquals('Hello', $foo->description_plain);
    }

    public function test_it_sets_orphan_category_parent_ids_to_null()
    {
        $child = Factory::create(new Category, ['parent_id' => 1]);
        $orphan = Factory::create(new Category, ['parent_id' => 0]);

        $this->assertEquals(1, $child->parent_id);
        $this->assertEquals(null, $orphan->parent_id);
    }
}