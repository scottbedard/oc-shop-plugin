<?php namespace Bedard\Shop\Tet\Unit\Repositories;

use Bedard\Shop\Models\Category;
use Bedard\Shop\Models\Product;
use Bedard\Shop\Repositories\CategoryRepository;
use Bedard\Shop\Tests\Factory;
use Bedard\Shop\Tests\PluginTestCase;

class CategoryRepositoryTest extends PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

    public function test_setting_explicit_column_selects()
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

    public function test_eager_loading_thumbnails()
    {
        $repository = new CategoryRepository;

        Factory::create(new Category);

        $thumbnails = $repository->get(['load_thumbnails' => true])->first()->toArray();
        $noThumbnails = $repository->get(['load_thumbnails' => false])->first()->toArray();

        $this->assertTrue(array_key_exists('thumbnails', $thumbnails));
        $this->assertFalse(array_key_exists('thumbnails', $noThumbnails));
    }
}
