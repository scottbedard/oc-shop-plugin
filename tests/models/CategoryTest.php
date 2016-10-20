<?php namespace Bedard\Shop\Tests\Models;

use Bedard\Shop\Models\Category;
use Bedard\Shop\Tests\Factory;

class CategoryTest extends \PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

    public function test_category_name_is_required()
    {
        $this->setExpectedException(\October\Rain\Database\ModelException::class);
        Factory::fill(new Category, null, ['name'])->validate();
    }

    public function test_category_slug_is_required()
    {
        $this->setExpectedException(\October\Rain\Database\ModelException::class);
        Factory::fill(new Category, null, ['slug'])->validate();
    }

    public function test_category_slug_must_be_unique()
    {
        $this->setExpectedException(\October\Rain\Database\ModelException::class);
        $category = Factory::create(new Category);
        Factory::create(new Category, ['slug' => $category->slug]);
    }
}
