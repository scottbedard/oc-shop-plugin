<?php namespace Bedard\Shop\Api;

use Bedard\Shop\Classes\ApiController;
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
            $data = input();

            return $repository->fetch($data);
        } catch (Exception $e) {
            Log::error($e->getMessage());

            abort(500, $e->getMessage());
        }
    }
}
