<?php namespace Bedard\Shop\Tests\Unit\Models;

use Bedard\Shop\Models\Category;
use Bedard\Shop\Models\Product;
use Bedard\Shop\Tests\Factory;
use Bedard\Shop\Tests\PluginTestCase;

class ProductTest extends PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

    public function test_product_name_is_required()
    {
        $this->setExpectedException(\October\Rain\Database\ModelException::class);
        Factory::fill(new Product, null, ['name'])->validate();
    }

    public function test_product_price_is_required()
    {
        $this->setExpectedException(\October\Rain\Database\ModelException::class);
        Factory::fill(new Product, null, ['price'])->validate();
    }

    public function test_product_price_must_be_a_positive_number()
    {
        $this->setExpectedException(\October\Rain\Database\ModelException::class);
        Factory::fill(new Product, ['price' => -1])->validate();
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
}
