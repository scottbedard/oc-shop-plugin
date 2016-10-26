<?php namespace Bedard\Shop\Tests\Unit\Models;

use Bedard\Shop\Models\Category;
use Bedard\Shop\Models\Product;
use Bedard\Shop\Tests\Factory;
use Bedard\Shop\Tests\PluginTestCase;

class CategoryTest extends PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

    public function test_category_doesnt_show_invalid_parents_in_dropdown()
    {
        $parent = Factory::create(new Category);
        $child = Factory::create(new Category, ['parent_id' => $parent->id]);

        $this->assertArrayEquals([0, $parent->id, $child->id], array_keys(Factory::fill(new Category)->getParentIdOptions()));
        $this->assertArrayEquals([0, $parent->id], array_keys($child->getParentIdOptions()));
        $this->assertArrayEquals([0], array_keys($parent->getParentIdOptions()));
    }

    public function test_category_name_is_required()
    {
        $this->setExpectedException(\October\Rain\Database\ModelException::class);
        Factory::fill(new Category, null, ['name'])->validate();
    }

    public function test_category_getChildIds_and_getParentIds()
    {
        $grandparent = Factory::create(new Category);
        $parent = Factory::create(new Category, ['parent_id' => $grandparent->id]);
        $child = Factory::create(new Category, ['parent_id' => $parent->id]);
        $grandchild = Factory::create(new Category, ['parent_id' => $child->id]);
        $orphan = Factory::create(new Category, ['parent_id' => null]);

        // // getChildIds
        $this->assertArrayEquals([2, 3, 4], Category::getChildIds($grandparent->id));
        $this->assertArrayEquals([3, 4], Category::getChildIds($parent->id));
        $this->assertArrayEquals([4], Category::getChildIds($child->id));
        $this->assertArrayEquals([], Category::getChildIds($grandchild->id));
        $this->assertArrayEquals([], Category::getChildIds($orphan->id));

        // getParentIds
        $this->assertArrayEquals([$child->id, $parent->id, $grandparent->id], Category::getParentIds($grandchild->id));
        $this->assertArrayEquals([$parent->id, $grandparent->id], Category::getParentIds($child->id));
        $this->assertArrayEquals([$grandparent->id], Category::getParentIds($parent->id));
        $this->assertArrayEquals([], Category::getParentIds($grandparent->id));
        $this->assertArrayEquals([], Category::getParentIds($orphan->id));
    }

    public function test_getParentIds_can_accept_an_array_of_ids()
    {
        $parent1 = Factory::create(new Category);
        $parent2 = Factory::create(new Category);
        $child1 = Factory::create(new Category, ['parent_id' => $parent1->id]);
        $child2 = Factory::create(new Category, ['parent_id' => $parent2->id]);

        $this->assertArrayEquals([$parent1->id, $parent2->id], Category::getParentIds([$child1->id, $child2->id]));
    }

    public function test_category_scopeIsChildOf_and_scopeIsNotChildOf()
    {
        $parent = Factory::create(new Category);
        $child = Factory::create(new Category, ['parent_id' => $parent->id]);
        $grandchild = Factory::create(new Category, ['parent_id' => $child->id]);

        $children = Category::isChildOf($parent->id)->get();
        $this->assertArrayEquals([$child->id, $grandchild->id], $children->lists('id'));

        $notChildren = Category::isNotChildOf($child->id)->get();
        $this->assertArrayEquals([$parent->id, $child->id], $notChildren->lists('id'));
    }

    public function test_category_scopeIsParentOf_and_scopeIsNotParentOf()
    {
        $parent = Factory::create(new Category);
        $child = Factory::create(new Category, ['parent_id' => $parent->id]);
        $grandchild = Factory::create(new Category, ['parent_id' => $child->id]);

        $parents = Category::isParentOf($child->id)->get();
        $this->assertArrayEquals([$parent->id], $parents->lists('id'));

        $notParents = Category::isNotParentOf($child->id)->get();
        $this->assertArrayEquals([$child->id, $grandchild->id], $notParents->lists('id'));
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

        $this->assertEquals(0, Category::isChildOf($baz->id)->count());

        Category::updateMany([
            ['id' => $foo->id, 'parent_id' => $bar->id],
            ['id' => $bar->id, 'parent_id' => $baz->id],
            ['id' => $baz->id, 'parent_id' => null],
        ]);

        $this->assertEquals(2, Category::isChildOf($baz->id)->count());
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

    public function test_changing_the_parent_id_syncs_all_products()
    {
        $parent1 = Factory::create(new Category);
        $parent2 = Factory::create(new Category);
        $child = Factory::create(new Category, ['parent_id' => $parent1->id]);
        $product = Factory::create(new Product);
        $product->categories()->sync([$child->id]);
        $product->syncInheritedCategories();

        $this->assertTrue($parent1->products()->where('id', $product->id)->exists());
        $this->assertFalse($parent2->products()->where('id', $product->id)->exists());
        $child->parent_id = $parent2->id;
        $child->save();
        $this->assertFalse($parent1->products()->where('id', $product->id)->exists());
        $this->assertTrue($parent2->products()->where('id', $product->id)->exists());
    }
}
