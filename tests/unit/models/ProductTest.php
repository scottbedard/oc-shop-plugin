<?php namespace Bedard\Shop\Tests\Unit\Models;

use Bedard\Shop\Models\Category;
use Bedard\Shop\Models\Price;
use Bedard\Shop\Models\Product;
use Bedard\Shop\Tests\Factory;
use Bedard\Shop\Tests\PluginTestCase;
use Carbon\Carbon;

class ProductTest extends PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

    public function test_product_name_is_required()
    {
        $this->setExpectedException(\October\Rain\Database\ModelException::class);
        Factory::fill(new Product, null, ['name'])->validate();
    }

    public function test_product_base_price_is_required()
    {
        $this->setExpectedException(\October\Rain\Database\ModelException::class);
        Factory::fill(new Product, null, ['base_price'])->validate();
    }

    public function test_product_base_price_must_be_a_positive_number()
    {
        $this->setExpectedException(\October\Rain\Database\ModelException::class);
        Factory::fill(new Product, ['base_price' => -1])->validate();
    }

    public function test_product_slug_is_required()
    {
        $this->setExpectedException(\October\Rain\Database\ModelException::class);
        Factory::fill(new Product, null, ['slug'])->validate();
    }

    public function test_product_slug_must_be_unique()
    {
        $this->setExpectedException(\October\Rain\Database\ModelException::class);
        $product = Factory::create(new Product);
        Factory::create(new Product, ['slug' => $product->slug]);
    }

    public function test_aliases_the_category_relationship_for_a_checkboxlist()
    {
        $cat1 = Factory::create(new Category, ['name' => 'cat1']);
        $cat2 = Factory::create(new Category, ['name' => 'cat2']);
        $product = Factory::create(new Product);

        $this->assertArrayEquals([], $product->categoriesList);

        $this->assertArrayEquals([
            $cat1->id => $cat1->name,
            $cat2->id => $cat2->name,
        ], $product->getCategoriesListOptions());

        $product->categoriesList = [$cat1->id];
        $product->save();

        $this->assertArrayEquals([$cat1->id], $product->categoriesList);
    }

    public function test_saving_a_product_sets_inherited_category_relationships()
    {
        $clothes = Factory::create(new Category);
        $shirts = Factory::create(new Category, ['parent_id' => $clothes->id]);
        $shirt = Factory::fill(new Product);
        $shirt->categoriesList = [$shirts->id];
        $shirt->save();

        $this->assertArrayEquals([$clothes->id, $shirts->id], Category::whereHas('products', function ($product) use ($shirt) {
            return $product->where('id', $shirt->id);
        })->lists('id'));
    }

    public function test_products_sync_their_inherited_categories()
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
        $product->syncInheritedCategories();

        $this->assertFalse($parent1->products()->where('id', $product->id)->exists());
        $this->assertTrue($parent2->products()->where('id', $product->id)->exists());
    }

    public function test_syncing_all_products_with_inherited_categories()
    {
        $parent1 = Factory::create(new Category);
        $parent2 = Factory::create(new Category);
        $child1 = Factory::create(new Category, ['parent_id' => $parent1->id]);
        $child2 = Factory::create(new Category, ['parent_id' => $parent2->id]);
        $product1 = Factory::create(new Product);
        $product2 = Factory::create(new Product);
        $product1->categories()->sync([$child1->id]);
        $product2->categories()->sync([$child2->id]);

        Product::syncAllInheritedCategories();

        $this->assertTrue($parent1->products()->where('id', $product1->id)->exists());
        $this->assertTrue($parent2->products()->where('id', $product2->id)->exists());
    }

    public function test_products_save_their_base_price()
    {
        $product = Factory::create(new Product);
        $this->assertEquals(1, Price::where('product_id', $product->id)->where('price', $product->base_price)->count());
    }

    public function test_current_price_relationship()
    {
        $product = Factory::create(new Product, ['base_price' => 5]);
        Factory::create(new Price, ['product_id' => $product->id, 'price' => 1, 'start_at' => Carbon::tomorrow()]);
        Factory::create(new Price, ['product_id' => $product->id, 'price' => 2]);
        Factory::create(new Price, ['product_id' => $product->id, 'price' => 3]);

        $product->load('current_price');
        $this->assertEquals(2, $product->current_price->price);
    }
}
