<?php namespace Bedard\Shop\Api;

use Bedard\Shop\Classes\ApiController;
use Bedard\Shop\Models\ApiSettings;
use Bedard\Shop\Repositories\CategoryRepository;
use Exception;
use Log;

class Categories extends ApiController
{
    /**
     * Fetch categories.
     *
     * @return \October\Rain\Database\Collection
     */
    public function index(CategoryRepository $repository)
    {
        try {
            $params = [
                'hide_empty' => ApiSettings::categoriesHideEmpty(),
                'load_thumbnails' => ApiSettings::categoriesLoadThumbnails(),
                'select' => ApiSettings::categoriesSelect(),
            ];

            return $repository->fetch($params);
        } catch (Exception $e) {
            Log::error($e->getMessage());

            abort(500, $e->getMessage());
        }
    }
}
