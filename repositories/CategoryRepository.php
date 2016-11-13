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
        $query = Category::isActive();

        if (array_key_exists('select', $params) && is_array($params['select']) && ! empty($params['select'])) {
            $query->select($params['select']);
        }

        if (array_key_exists('hide_empty', $params) && $params['hide_empty']) {
            $query->has('products');
        }

        if (array_key_exists('load_thumbnails', $params) && $params['load_thumbnails']) {
            $query->with('thumbnails');
        }

        return $query->get();
    }

    /**
     * Find a category.
     *
     * @param  string   $slug
     * @param  array    $params
     * @return \Bedard\Shop\Models\Category
     */
    public function find($slug, array $params = [])
    {
        $query = Category::isActive()->whereSlug($slug);

        if (array_key_exists('select', $params) && $params['select']) {
            $query->select($params['select']);
        }

        $category = $query->firstOrFail();

        $loadProducts = array_key_exists('load_products', $params) && $params['load_products'];
        if ($loadProducts) {
            $category->load(['products' => function ($products) use ($category, $params) {
                if (array_key_exists('products_select', $params) && $params['products_select']) {
                    $products->select($params['products_select']);

                    if (in_array('price', $params['products_select'])) {
                        $products->joinPrice();
                    }
                }

                if (! is_null($category->product_sort_column) &&
                    ! is_null($category->product_sort_direction)) {
                    $products->orderBy($category->product_sort_column, $category->product_sort_direction);
                }
            }]);

            if ($loadProducts &&
                array_key_exists('load_products_thumbnails', $params) &&
                $params['load_products_thumbnails']) {
                $category->products->load('thumbnails');
            }
        }

        if (array_key_exists('load_thumbnails', $params) && $params['load_thumbnails']) {
            $category->load('thumbnails');
        }

        return $category;
    }
}
