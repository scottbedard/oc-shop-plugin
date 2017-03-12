<?php namespace Bedard\Shop\Api;

use Bedard\Shop\Classes\ApiController;
use Bedard\Shop\Repositories\CategoryRepository;

class CategoriesApi extends ApiController
{
    /**
     * List categories.
     *
     * @param  CategoryRepository               $repository
     * @return October\Rain\Database\Collection
     */
    public function index(CategoryRepository $repository)
    {
        $query = input();

        return $repository->get($query);
    }

    /**
     * Find a category.
     *
     * @param  CategoryRepository           $repository
     * @param  string                       $slug
     * @return \Bedard\Shop\Models\Category
     */
    public function show(CategoryRepository $repository, $slug)
    {
        $query = input();

        return $repository->find($slug, $query);
    }
}
