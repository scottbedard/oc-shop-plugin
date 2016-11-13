<?php namespace Bedard\Shop\Tet\Unit\Repositories;

use Bedard\Shop\Models\Category;
use Bedard\Shop\Models\Product;
use Bedard\Shop\Repositories\CategoryRepository;
use Bedard\Shop\Tests\Factory;
use Bedard\Shop\Tests\PluginTestCase;
use Exception;

class CategoryRepositoryTest extends PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

    public function test_setting_explicit_column_selects()
    {
        $category = Factory::create(new Category);
        $repository = new CategoryRepository;
        $results = $repository->get(['select' => ['id']])->first()->toArray();
        
        $this->assertTrue(array_key_exists('id', $results));
        $this->assertFalse(array_key_exists('name', $results));
    }

    public function test_hiding_empty_categories()
    {
        $category1 = Factory::create(new Category);
        $category2 = Factory::create(new Category);
        $product = Factory::create(new Product);
        $product->categories()->sync([$category1->id]);

        $repository = new CategoryRepository;

        $hiding = $repository->get(['hide_empty' => true]);
        $notHiding = $repository->get(['hide_empty' => false]);

        $this->assertEquals(1, $hiding->count());
        $this->assertEquals($category1->id, $hiding->first()->id);
        $this->assertEquals(2, $notHiding->count());
    }
}
