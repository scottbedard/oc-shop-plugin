<?php namespace Bedard\Shop\Tests\Unit\Models;

use Bedard\Shop\Models\Category;
use Bedard\Shop\Models\Discount;
use Bedard\Shop\Models\Price;
use Bedard\Shop\Models\Product;
use Bedard\Shop\Tests\Factory;
use Bedard\Shop\Tests\PluginTestCase;
use Carbon\Carbon;

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
        $child = Factory::create(new Category, ['parent_id' => 2]);
        $orphan = Factory::create(new Category, ['parent_id' => 0]);

        $this->assertEquals(2, $child->parent_id);
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

    public function test_getting_tree_of_children()
    {
        $c1 = Factory::create(new Category);
        $c2 = Factory::create(new Category, ['parent_id' => $c1->id]);
        $c3 = Factory::create(new Category, ['parent_id' => $c2->id]);
        $c4 = Factory::create(new Category, ['parent_id' => $c3->id]);
        $c5 = Factory::create(new Category, ['parent_id' => $c2->id]);
        $c6 = Factory::create(new Category);

        $parentIds = Category::getParentCategoryIds();
        $this->assertArrayEquals([], $parentIds[$c1->id]);
        $this->assertArrayEquals([$c1->id], $parentIds[$c2->id]);
        $this->assertArrayEquals([$c1->id, $c2->id], $parentIds[$c3->id]);
        $this->assertArrayEquals([$c1->id, $c2->id, $c3->id], $parentIds[$c4->id]);
        $this->assertArrayEquals([$c1->id, $c2->id], $parentIds[$c5->id]);
        $this->assertArrayEquals([], $parentIds[$c6->id]);
    }

    public function test_product_inherited_is_updated_when_category_nesting_changes()
    {
        $parent1 = Factory::create(new Category);
        $parent2 = Factory::create(new Category);
        $child1 = Factory::create(new Category, ['parent_id' => $parent1->id]);
        $product = Factory::create(new Product);
        $product->categories()->sync([$child1->id]);

        Product::syncAllInheritedCategories();
        $this->assertArrayEquals([$product->id], $parent1->products()->lists('id'));
        $this->assertArrayEquals([$product->id], $child1->products()->lists('id'));
        $this->assertEquals(0, $parent2->products()->count());

        $child1->parent_id = $parent2->id;
        $child1->save();
        $this->assertArrayEquals([$product->id], $parent2->products()->lists('id'));
        $this->assertArrayEquals([$product->id], $child1->products()->lists('id'));
        $this->assertEquals(0, $parent1->products()->count());
    }

    public function test_discounts_are_resynced_when_a_category_is_deleted()
    {
        $parent = Factory::create(new Category);
        $child = Factory::create(new Category, ['parent_id' => $parent->id]);
        $grandchild = Factory::create(new Category, ['parent_id' => $child->id]);
        $product = Factory::create(new Product);
        $product->categories()->sync([$grandchild->id]);
        Product::syncAllInheritedCategories();

        $discount = Factory::create(new Discount);
        $discount->categories()->sync([$parent->id]);
        Discount::syncAllPrices();

        $this->assertEquals(1, Price::whereProductId($product->id)->whereDiscountId($discount->id)->count());
        $child->delete();
        $this->assertEquals(0, Price::whereProductId($product->id)->whereDiscountId($discount->id)->count());
    }

    public function test_isActive_and_isNotActive_scopes()
    {
        $active = Factory::create(new Category, ['is_active' => true]);
        $inactive = Factory::create(new Category, ['is_active' => false]);

        $this->assertEquals(1, Category::isActive()->count());
        $this->assertEquals(1, Category::isNotActive()->count());
        $this->assertEquals($active->id, Category::isActive()->first()->id);
        $this->assertEquals($inactive->id, Category::isNotActive()->first()->id);
    }

    public function test_setting_product_sort()
    {
        $standard = Factory::create(new Category, ['product_sort' => 'foo:bar']);
        $this->assertEquals('foo', $standard->product_sort_column);
        $this->assertEquals('bar', $standard->product_sort_direction);
        $this->assertEquals(false, $standard->isCustomSorted());

        $custom = Factory::create(new Category, ['product_sort' => 'custom']);
        $this->assertEquals(null, $custom->product_sort_column);
        $this->assertEquals(null, $custom->product_sort_direction);
        $this->assertEquals(true, $custom->isCustomSorted());
    }

    public function test_creating_updating_and_deleting_filters()
    {
        // create
        $category = Factory::create(new Category, [
            'category_filters' => [
                [
                    'comparator' => '<',
                    'id' => null,
                    'is_deleted' => false,
                    'left' => 'foo',
                    'right' => 'bar',
                    'value' => 0,
                ],
            ],
        ]);

        $this->assertTrue($category->isFiltered());
        $this->assertEquals(1, $category->filters()->count());
        $filter = $category->filters()->first();

        // update
        $category->category_filters = [
            [
                'comparator' => '>',
                'id' => $filter->id,
                'is_deleted' => false,
                'left' => 'hello',
                'right' => 'world',
                'value' => 1.23,
            ],
        ];

        $category->save();
        $filter = $category->filters()->first();
        $this->assertEquals('>', $filter->comparator);
        $this->assertEquals('hello', $filter->left);
        $this->assertEquals('world', $filter->right);
        $this->assertEquals(1.23, $filter->value);

        // delete
        $category->category_filters = [
            [
                'id' => $filter->id,
                'is_deleted' => true,
            ],
        ];

        $category->save();
        $category->load('filters');
        $this->assertFalse($category->isFiltered());
        $this->assertEquals(0, $category->filters()->count());
    }

    public function test_loading_products_of_a_category_filtered_by_explicit_price()
    {
        $product1 = Factory::create(new Product, ['base_price' => 10]);
        $product2 = Factory::create(new Product, ['base_price' => 20]);
        $category = Factory::create(new Category, [
            'category_filters' => [
                [
                    'comparator' => '<',
                    'id' => null,
                    'is_deleted' => false,
                    'left' => 'base_price',
                    'right' => 'custom',
                    'value' => 15,
                ],
            ],
        ]);

        $product1->categories()->sync([$category->id]);
        $product2->categories()->sync([$category->id]);

        $products = $category->getProducts();
        $this->assertEquals(1, $products->count());
        $this->assertEquals($product1->id, $products->first()->id);
    }

    public function test_loading_products_of_a_category_filtered_by_relative_price()
    {
        $product1 = Factory::create(new Product, ['base_price' => 10]);
        $product2 = Factory::create(new Product, ['base_price' => 20]);
        $discount = Factory::create(new Discount, ['amount_exact' => 5, 'is_percentage' => false]);
        $discount->products()->sync([$product2->id]);
        $discount->save();

        $category = Factory::create(new Category, [
            'category_filters' => [
                [
                    'comparator' => '<',
                    'id' => null,
                    'is_deleted' => false,
                    'left' => 'price',
                    'right' => 'base_price',
                    'value' => 0,
                ],
            ],
        ]);

        $product1->categories()->sync([$category->id]);
        $product2->categories()->sync([$category->id]);

        $products = $category->getProducts(['products_select' => ['price']]);
        $this->assertEquals(1, $products->count());
        $this->assertEquals($product2->id, $products->first()->id);
    }

    public function test_loading_products_of_a_category_filtered_by_date()
    {
        $product1 = Factory::create(new Product);
        $product1->created_at = Carbon::now()->subDays(1);
        $product1->save();

        $product2 = Factory::create(new Product);

        $category = Factory::create(new Category, [
            'category_filters' => [
                [
                    'comparator' => '>',
                    'id' => null,
                    'is_deleted' => false,
                    'left' => 'created_at',
                    'right' => 'custom',
                    'value' => 1,
                ],
            ],
        ]);

        $category->products()->sync([$product1->id, $product2->id]);

        $products = $category->getProducts();
        $this->assertEquals(1, $products->count());
        $this->assertEquals($product2->id, $products->first()->id);
    }

    public function test_sorting_products_by_default_column()
    {
        $product1 = Factory::create(new Product, ['name' => 'c']);
        $product2 = Factory::create(new Product, ['name' => 'b']);
        $product3 = Factory::create(new Product, ['name' => 'a']);
        $category = Factory::create(new Category, ['product_sort' => 'name:asc']);
        $category->products()->sync([$product1->id, $product2->id, $product3->id]);

        $products = $category->getProducts();

        $this->assertEquals($product3->id, $products[0]->id);
        $this->assertEquals($product2->id, $products[1]->id);
        $this->assertEquals($product1->id, $products[2]->id);
    }

    public function test_sorting_products_by_defined_order()
    {
        $product1 = Factory::create(new Product, ['name' => 'c']);
        $product2 = Factory::create(new Product, ['name' => 'b']);
        $product3 = Factory::create(new Product, ['name' => 'a']);
        $category = Factory::create(new Category, [
            'product_sort' => 'custom',
            'product_order' => [$product2->id, $product1->id, $product3->id],
        ]);
        $category->products()->sync([$product1->id, $product2->id, $product3->id]);
        $category->save();

        $products = $category->getProducts();

        $this->assertEquals($product2->id, $products[0]->id);
        $this->assertEquals($product1->id, $products[1]->id);
        $this->assertEquals($product3->id, $products[2]->id);
    }

    public function test_sorting_products_by_external_params()
    {
        $product1 = Factory::create(new Product, ['name' => 'c']);
        $product2 = Factory::create(new Product, ['name' => 'b']);
        $product3 = Factory::create(new Product, ['name' => 'a']);
        $category = Factory::create(new Category);
        $category->products()->sync([$product1->id, $product2->id, $product3->id]);

        $products = $category->getProducts([
            'products_sort_column' => 'name',
            'products_sort_direction' => 'asc',
        ]);

        $this->assertEquals($product3->id, $products[0]->id);
        $this->assertEquals($product2->id, $products[1]->id);
        $this->assertEquals($product1->id, $products[2]->id);
    }

    public function test_isPaginated_method()
    {
        $paginated = Factory::fill(new Category, ['product_rows' => 1]);
        $notPaginated = Factory::fill(new Category, ['product_rows' => 0]);

        $this->assertTrue($paginated->isPaginated());
        $this->assertFalse($notPaginated->isPaginated());
    }

    public function test_paginating_results()
    {
        $product1 = Factory::create(new Product);
        $product2 = Factory::create(new Product);
        $product3 = Factory::create(new Product);
        $product4 = Factory::create(new Product);
        $product5 = Factory::create(new Product);
        $category = Factory::create(new Category, [
            'product_columns' => 2,
            'product_rows' => 1,
            'product_sort_column' => 'id',
            'product_sort_direction' => 'asc',
        ]);

        $category->products()->sync([
            $product1->id,
            $product2->id,
            $product3->id,
            $product4->id,
            $product5->id,
        ]);

        $page1 = $category->getProducts(['page' => 1]);
        $page2 = $category->getProducts(['page' => 2]);
        $page3 = $category->getProducts(['page' => 3]);
        print_r($page1->toArray());
        print_r($page2->toArray());
        print_r($page3->toArray());
        $this->assertEquals(2, $page1->count());
        $this->assertEquals(1, $page1[0]->id);
        $this->assertEquals(2, $page1[1]->id);

        $this->assertEquals(2, $page2->count());
        $this->assertEquals(3, $page2[0]->id);
        $this->assertEquals(4, $page2[1]->id);

        $this->assertEquals(1, $page3->count());
        $this->assertEquals(5, $page3[0]->id);
    }
}
