<?php namespace Bedard\Shop\Repositories;

use Bedard\Shop\Models\Category;
use Exception;

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
        if (! array_key_exists('select', $params) || ! is_array($params['select']) || empty($params['select'])) {
            throw new Exception('Categories fetch() must select at least one column.');
        }

        $query = Category::isActive()->select($params['select']);

        // hide empty categories
        if ($params['hide_empty']) {
            $query->has('products');
        }

        // eager load thumbnails
        if ($params['load_thumbnails']) {
            $query->with('thumbnails');
        }

        return $query->get();
    }
}
