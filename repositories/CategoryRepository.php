<?php namespace Bedard\Shop\Repositories;

use Bedard\Shop\Classes\Repository;
use Bedard\Shop\Models\Category;

class CategoryRepository extends Repository
{
    /**
     * Find a category.
     *
     * @param  string                       $slug
     * @param  array                        $params
     * @param  array                        $options
     * @return Bedard\Shop\Models\Category
     */
    public function find($slug, array $params = [], array $options = [])
    {
        $query = Category::whereSlug($slug);
        $this->selectColumns($query, $options);
        $this->withRelationships($query, $options);

        return $query->firstOrFail();
    }

    /**
     * Fetch categories.
     *
     * @param  array                            $params
     * @param  array                            $options
     * @return October\Rain\Database\Collection
     */
    public function get(array $params = [], array $options = [])
    {
        $query = (new Category)->newQuery();
        $count = (new Category)->newQuery();
        $this->selectColumns($query, $options);
        $this->withRelationships($query, $options);

        return [
            'results' => $query->get(),
            'total' => $count->count(),
        ];
    }
}
