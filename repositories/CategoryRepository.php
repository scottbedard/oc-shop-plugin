<?php namespace Bedard\Shop\Repositories;

use Bedard\Shop\Models\Category;

class CategoryRepository
{
    /**
     * Fetch categories.
     *
     * @param  array $params
     * @return \October\Rain\Database\Collection
     */
    public function fetch(array $params = [])
    {
        return Category::get();
    }
}
