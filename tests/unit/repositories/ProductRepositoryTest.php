<?php namespace Bedard\Shop\Tests\Unit\Repositories;

use Bedard\Shop\Classes\Factory;
use Bedard\Shop\Models\Category;
use Bedard\Shop\Models\Product;
use Bedard\Shop\Repositories\ProductRepository;
use Bedard\Shop\Tests\Unit\ShopTestCase;

class ProductRepositoryTest extends ShopTestCase
{
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

        $data = $repository->get([], ['columns' => ['name']]);

        $this->assertFalse(array_key_exists('id', $data['results']->toArray()[0]));
        $this->assertTrue(array_key_exists('name', $data['results']->toArray()[0]));
    }

    public function test_eager_loading_products_relationships()
    {
        $repository = new ProductRepository;
        $category = Factory::create(new Category);
        $product = Factory::create(new Product);
        $product->categories()->attach($category);

        $data = $repository->get([], ['relationships' => ['categories']]);

        $this->assertEquals($category->id, $data['results']->toArray()[0]['categories'][0]['id']);
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

        $data = $repository->get(['categories' => [$category1->slug, $category2->slug]]);

        $this->assertEquals($foo->id, $data['results']->toArray()[0]['id']);
        $this->assertEquals($bar->id, $data['results']->toArray()[1]['id']);
        $this->assertEquals(2, $data['total']);
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

        $data = $repository->get(['categories' => [$category1->slug, $category2->slug]]);

        $this->assertEquals($foo->id, $data['results']->toArray()[0]['id']);
        $this->assertEquals($bar->id, $data['results']->toArray()[1]['id']);
        $this->assertEquals(2, $data['total']);
    }

    public function test_paginated_results()
    {
        $repository = new ProductRepository;
        $foo = Factory::create(new Product);
        $bar = Factory::create(new Product);
        $baz = Factory::create(new Product);

        $this->assertEquals($foo->id, $repository->get(['take' => 1])['results'][0]->id);
        $this->assertEquals($bar->id, $repository->get(['skip' => 1, 'take' => 1])['results'][0]->id);
        $this->assertEquals([$bar->id, $baz->id], $repository->get(['skip' => 1])['results']->lists('id'));
        $this->assertEquals(3, $repository->get(['skip' => 1, 'take' => 1])['total']);
    }
}
