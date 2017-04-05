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
    //
    // public function test_selecting_specific_categories_columns()
    // {
    //     $repository = new CategoryRepository;
    //     $category = Factory::create(new Category);
    //
    //     $categories = $repository->get([], ['columns' => ['name']])->toArray();
    //
    //     $this->assertFalse(array_key_exists('id', $categories[0]));
    //     $this->assertTrue(array_key_exists('name', $categories[0]));
    // }
    //
    // public function test_eager_loading_categories_relationships()
    // {
    //     $repository = new CategoryRepository;
    //     $category = Factory::create(new Category);
    //     $product = Factory::create(new Product);
    //     $product->categories()->attach($category);
    //
    //     $categories = $repository->get([], ['relationships' => ['products']])->toArray();
    //
    //     $this->assertEquals($product->id, $categories[0]['products'][0]['id']);
    // }
}
