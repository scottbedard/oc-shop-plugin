<?php namespace Bedard\Shop\Api;

use Bedard\Shop\Classes\ApiController;
use Bedard\Shop\Repositories\CategoryRepository;

class CategoriesApi extends ApiController
{
    /**
     * List categories.
     *
     * @return October\Rain\Database\Collection
     */
    public function index(CategoryRepository $repository)
    {
        $query = input();

        return $repository->get($query);
    }
}
