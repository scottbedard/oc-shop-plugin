<?php namespace Bedard\Shop\Tests\Unit\Models;

use Bedard\Shop\Classes\Factory;
use Bedard\Shop\Models\Category;
use Bedard\Shop\Models\Product;
use Bedard\Shop\Repositories\ProductRepository;
use PluginTestCase;

class ProductRepositoryTest extends PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

    public function test_selecting_specific_product_columns()
    {
        $repository = new ProductRepository;
        $product = Factory::create(new Product);

        $data = $repository->find($product->slug, [], ['columns' => ['name']])->toArray();

        $this->assertFalse(array_key_exists('id', $data));
        $this->assertTrue(array_key_exists('name', $data));
    }

    public function test_eager_loading_product_relationships()
    {
        $repository = new ProductRepository;
        $category = Factory::create(new Category);
        $product = Factory::create(new Product);
        $product->categories()->attach($category);

        $data = $repository->find($product->slug, [], ['relationships' => ['categories']])->toArray();

        $this->assertEquals($category->id, $data['categories'][0]['id']);
    }

    public function test_selecting_specific_products_columns()
    {
        $repository = new ProductRepository;
        $product = Factory::create(new Product);

        $products = $repository->get([], ['columns' => ['name']])->toArray();

        $this->assertFalse(array_key_exists('id', $products[0]));
        $this->assertTrue(array_key_exists('name', $products[0]));
    }

    public function test_eager_loading_products_relationships()
    {
        $repository = new ProductRepository;
        $category = Factory::create(new Category);
        $product = Factory::create(new Product);
        $product->categories()->attach($category);

        $products = $repository->get([], ['relationships' => ['categories']])->toArray();

        $this->assertEquals($category->id, $products[0]['categories'][0]['id']);
    }

    public function test_products_in_a_category_via_csv()
    {
        $repository = new ProductRepository;
        $category1 = Factory::create(new Category);
        $category2 = Factory::create(new Category);
        $foo = Factory::create(new Product);
        $bar = Factory::create(new Product);
        $baz = Factory::create(new Product);
        $foo->categories()->attach($category1);
        $bar->categories()->attach($category2);

        $products = $repository->get(['categories' => "{$category1->slug},{$category2->slug}"])->toArray();

        $this->assertEquals($foo->id, $products[0]['id']);
        $this->assertEquals($bar->id, $products[1]['id']);
        $this->assertEquals(2, count($products));
    }

    public function test_products_in_a_category_via_array()
    {
        $repository = new ProductRepository;
        $category1 = Factory::create(new Category);
        $category2 = Factory::create(new Category);
        $foo = Factory::create(new Product);
        $bar = Factory::create(new Product);
        $baz = Factory::create(new Product);
        $foo->categories()->attach($category1);
        $bar->categories()->attach($category2);

        $products = $repository->get(['categories' => [$category1->slug, $category2->slug]])->toArray();

        $this->assertEquals($foo->id, $products[0]['id']);
        $this->assertEquals($bar->id, $products[1]['id']);
        $this->assertEquals(2, count($products));
    }
}
