<?php namespace Bedard\Shop\Api;

use Log;
use Exception;
use Bedard\Shop\Models\ApiSettings;
use Bedard\Shop\Classes\ApiController;
use Bedard\Shop\Repositories\CategoryRepository;

class Categories extends ApiController
{
    /**
     * List all categories.
     *
     * @return \October\Rain\Database\Collection
     */
    public function index(CategoryRepository $repository)
    {
        try {
            $params = [
                'hide_empty' => ApiSettings::categoriesHideEmpty(),
                'load_thumbnails' => ApiSettings::categoriesLoadThumbnails(),
            ];

            return $repository->get($params);
        } catch (Exception $e) {
            Log::error($e->getMessage());

            abort(500, $e->getMessage());
        }
    }

    /**
     * Find a single category.
     *
     * @param  \Bedard\Shop\Repositories\CategoryRepository $repository
     * @param  string                                       $slug
     * @return \Bedard\Shop\Models\Category
     */
    public function show(CategoryRepository $repository, $slug)
    {
        try {
            $params = [
                'load_thumbnails' => ApiSettings::categoryLoadThumbnails(),
            ];

            return $repository->find($slug, $params);
        } catch (Exception $e) {
            Log::error($e->getMessage());

            abort(500, $e->getMessage());
        }
    }

    /**
     * List the products in a category.
     *
     * @param  CategoryRepository $repository
     * @param  string             $slug
     * @return \October\Rain\Database\Collection
     */
    public function products(CategoryRepository $repository, $slug)
    {
        try {
            $params = [
                'load_thumbnails' => ApiSettings::categoryLoadThumbnails(),
            ];

            return $repository->products($slug, $params);
        } catch (Exception $e) {
            Log::error($e->getMessage());

            abort(500, $e->getMessage());
        }
    }
}
