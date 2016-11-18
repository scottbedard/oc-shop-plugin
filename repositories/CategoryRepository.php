<?php namespace Bedard\Shop\Repositories;

use Bedard\Shop\Models\Category;

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
        $query = Category::isActive()->with('filters')->whereSlug($slug);

        if (array_key_exists('select', $params) && $params['select']) {
            $query->select($params['select']);
        }

        $category = $query->firstOrFail();

        if (array_key_exists('load_products', $params) && $params['load_products']) {
            $category->products = $category->getProducts($params);
        }

        if (array_key_exists('load_thumbnails', $params) && $params['load_thumbnails']) {
            $category->load('thumbnails');
        }

        return $category;
    }

    /**
     * Fetch the products in a category.
     *
     * @param  string   $slug
     * @param  array    $params
     * @return \October\Rain\Database\Collection
     */
    public function products($slug, array $params = [])
    {
        $category = Category::isActive()
            ->with('filters')
            ->whereSlug($slug)
            ->firstOrFail();

        return $category->getProducts($params);
    }
}
