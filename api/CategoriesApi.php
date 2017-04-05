<?php namespace Bedard\Shop\Api;

use Bedard\Shop\Classes\ApiController;
use Bedard\Shop\Models\ApiSettings;
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
        $options = ApiSettings::getCategoriesOptions();

        if (! $options['is_enabled']) {
            return abort(403, 'Forbidden');
        }

        return $repository->get($query, $options);
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
        $options = ApiSettings::getCategoryOptions();

        if (! $options['is_enabled']) {
            return abort(403, 'Forbidden');
        }

        return $repository->find($slug, $query, $options);
    }
}
