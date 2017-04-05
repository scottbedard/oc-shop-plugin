<?php namespace Bedard\Shop\Tests\Unit\Models;

use Bedard\Shop\Classes\Factory;
use Bedard\Shop\Models\Category;
use Bedard\Shop\Models\Product;
use Bedard\Shop\Repositories\CategoryRepository;
use PluginTestCase;

class CategoryRepositoryTest extends PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

    public function test_selecting_specific_category_columns()
    {
        $repository = new CategoryRepository;
        $category = Factory::create(new Category);

        $data = $repository->find($category->slug, [], ['columns' => ['name']])->toArray();

        $this->assertFalse(array_key_exists('id', $data));
        $this->assertTrue(array_key_exists('name', $data));
    }

    public function test_eager_loading_category_relationships()
    {
        $repository = new CategoryRepository;
        $category = Factory::create(new Category);
        $product = Factory::create(new Product);
        $product->categories()->attach($category);

        $data = $repository->find($category->slug, [], ['relationships' => ['products']])->toArray();

        $this->assertEquals($product->id, $data['products'][0]['id']);
    }

    public function test_selecting_specific_categories_columns()
    {
        $repository = new CategoryRepository;
        $category = Factory::create(new Category);

        $categories = $repository->get([], ['columns' => ['name']])->toArray();

        $this->assertFalse(array_key_exists('id', $categories[0]));
        $this->assertTrue(array_key_exists('name', $categories[0]));
    }

    public function test_eager_loading_categories_relationships()
    {
        $repository = new CategoryRepository;
        $category = Factory::create(new Category);
        $product = Factory::create(new Product);
        $product->categories()->attach($category);

        $categories = $repository->get([], ['relationships' => ['products']])->toArray();

        $this->assertEquals($product->id, $categories[0]['products'][0]['id']);
    }
}
