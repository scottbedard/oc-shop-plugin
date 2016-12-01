<?php namespace Bedard\Shop\Tet\Unit\Repositories;

use Bedard\Shop\Models\Category;
use Bedard\Shop\Models\Product;
use Bedard\Shop\Repositories\CategoryRepository;
use Bedard\Shop\Tests\Factory;
use Bedard\Shop\Tests\PluginTestCase;

class CategoryRepositoryTest extends PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

    public function test_hiding_empty_categories()
    {
        $repository = new CategoryRepository;

        $category1 = Factory::create(new Category);
        $category2 = Factory::create(new Category);
        $product = Factory::create(new Product);
        $product->categories()->sync([$category1->id]);

        $hiding = $repository->get(['hide_empty' => true]);
        $notHiding = $repository->get(['hide_empty' => false]);

        $this->assertEquals(1, $hiding->count());
        $this->assertEquals($category1->id, $hiding->first()->id);
        $this->assertEquals(2, $notHiding->count());
    }

    public function test_eager_loading_multiple_category_thumbnails()
    {
        $repository = new CategoryRepository;

        Factory::create(new Category);

        $thumbnails = $repository->get(['load_thumbnails' => true])->first()->toArray();
        $noThumbnails = $repository->get(['load_thumbnails' => false])->first()->toArray();

        $this->assertTrue(array_key_exists('thumbnails', $thumbnails));
        $this->assertFalse(array_key_exists('thumbnails', $noThumbnails));
    }

    public function test_eager_loading_single_category_products()
    {
        $repository = new CategoryRepository;

        $category = Factory::create(new Category);
        $withProducts = $repository->find($category->slug, ['load_products' => true])->toArray();
        $withoutProducts = $repository->find($category->slug, ['load_products' => false])->toArray();

        $this->assertTrue(array_key_exists('products', $withProducts));
        $this->assertFalse(array_key_exists('products', $withoutProducts));
    }

    public function test_eager_loading_single_category_thumbnails()
    {
        $repository = new CategoryRepository;

        $category = Factory::create(new Category);
        $withThumbnails = $repository->find($category->slug, ['load_thumbnails' => true])->toArray();
        $withoutThumbnails = $repository->find($category->slug, ['load_thumbnails' => false])->toArray();

        $this->assertTrue(array_key_exists('thumbnails', $withThumbnails));
        $this->assertFalse(array_key_exists('thumbnails', $withoutThumbnails));
    }

    public function test_fetching_the_products_in_a_category()
    {
        $product1 = Factory::create(new Product);
        $product2 = Factory::create(new Product);
        $category = Factory::create(new Category);
        $category->products()->sync([$product1->id]);

        $repository = new CategoryRepository;

        $products = $repository->products($category->slug, []);
        $this->assertEquals(1, $products->count());
        $this->assertEquals($product1->id, $products->first()->id);
    }
}
