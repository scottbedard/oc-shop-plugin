<?php namespace Bedard\Shop\Repositories;

use Bedard\Shop\Models\Category;
use Exception;

class CategoryRepository
{
    /**
     * Get categories.
     *
     * @param  array $params
     * @return \October\Rain\Database\Collection
     */
    public function get(array $params = [])
    {
        if (! array_key_exists('select', $params) || ! is_array($params['select']) || empty($params['select'])) {
            throw new Exception('Categories get() must select at least one column.');
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

    /**
     * Show a category.
     *
     * @param  string   $slug
     * @param  array    $params
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     * @return \Bedard\Shop\Models\Category
     */
    public function show($slug, array $params = [])
    {
        if (! array_key_exists('select', $params) || ! is_array($params['select']) || empty($params['select'])) {
            throw new Exception('Category show() must select at least one column.');
        }

        $category = Category::isActive()
            ->select($params['select'])
            ->whereSlug($slug)
            ->firstOrFail();

        if ($params['load_products']) {
            $category->load(['products' => function($products) use ($category, $params) {
                if (! array_key_exists('products_select', $params) ||
                    ! is_array($params['products_select']) ||
                    empty($params['products_select'])) {
                    throw new Exception('Category show() products must select at least one column.');
                }

                $products->select($params['products_select']);

                if (! is_null($category->product_sort_column) &&
                    ! is_null($category->product_sort_direction)) {
                    $products->orderBy($category->product_sort_column, $category->product_sort_direction);
                }
            }]);

            if ($params['load_products_thumbnails']) {
                $category->products->load('thumbnails');
            }
        }

        if ($params['load_thumbnails']) {
            $category->load('thumbnails');
        }

        return $category;
    }
}
