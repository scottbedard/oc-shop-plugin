<?php namespace Bedard\Shop\Tet\Unit\Repositories;

use Bedard\Shop\Repositories\CategoryRepository;
use Bedard\Shop\Tests\PluginTestCase;
use Exception;

class CategoryTest extends PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

    public function test_listing_all_categories_required_a_select_parameter()
    {
        $this->setExpectedException(Exception::class);
        $repository = new CategoryRepository;
        $repository->get();
    }
}
