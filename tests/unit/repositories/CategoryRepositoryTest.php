<?php namespace Bedard\Shop\Tet\Unit\Repositories;

use Bedard\Shop\Models\Category;
use Bedard\Shop\Models\Product;
use Bedard\Shop\Repositories\CategoryRepository;
use Bedard\Shop\Tests\Factory;
use Bedard\Shop\Tests\PluginTestCase;

class CategoryRepositoryTest extends PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

    public function test_selecting_multiple_categories_columns()
    {
        $repository = new CategoryRepository;

        Factory::create(new Category);
        $results = $repository->get(['select' => ['id']])->first()->toArray();

        $this->assertTrue(array_key_exists('id', $results));
        $this->assertFalse(array_key_exists('name', $results));
    }

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

    public function test_selecting_single_category_colums()
    {
        $repository = new CategoryRepository;

        $category = Factory::create(new Category);
        $allColumns = $repository->find($category->slug);
        $selectedColumns = $repository->find($category->slug, ['select' => ['id']]);

        $this->assertEquals($category->name, $allColumns->name);
        $this->assertEquals($category->id, $selectedColumns->id);
        $this->assertNull($selectedColumns->name);
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
}
