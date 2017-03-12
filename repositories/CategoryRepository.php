<?php namespace Bedard\Shop\Repositories;

use Bedard\Shop\Models\Category;
use Bedard\Shop\Classes\Repository;

class CategoryRepository extends Repository
{
    /**
     * Fetch categories.
     *
     * @return October\Rain\Database\Collection
     */
    public function get($query)
    {
        $categories = Category::get();

        return $categories;
    }
}
