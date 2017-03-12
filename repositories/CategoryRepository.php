<?php namespace Bedard\Shop\Repositories;

use Bedard\Shop\Models\Category;
use Bedard\Shop\Classes\Repository;

class CategoryRepository extends Repository
{
    /**
     * Find a category.
     *
     * @param  string                       $slug
     * @param  array                        $options
     * @return Bedard\Shop\Models\Category
     */
    public function find($slug, array $options = [])
    {
        $query = Category::whereSlug($slug);

        // eager load related models
        $query = $this->queryWith($query, $options);

        return $query->firstOrFail();
    }

    /**
     * Fetch categories.
     *
     * @param  array                            $options
     * @return October\Rain\Database\Collection
     */
    public function get(array $options = [])
    {
        $query = (new Category)->newQuery();

        // eager load related models
        $query = $this->queryWith($query, $options);

        return $query->get();
    }
}
