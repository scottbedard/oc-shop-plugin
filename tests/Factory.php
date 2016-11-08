<?php namespace Bedard\Shop\Tests;

use Faker;
use Faker\Provider\Lorem;
use Model;

class Factory
{
    /**
     * Create a model and save it to the database.
     *
     * @param  Model    $model  Model to create
     * @param  array    $data   Data to fill model with
     * @return Model
     */
    public static function create(Model $model, $data = [], $without = [])
    {
        $model = self::fill($model, $data, $without);
        $model->save();

        return $model;
    }

    /**
     * Create a model and fill it with data.
     *
     * @param  Model    $model  Model to fill
     * @param  array    $data   Data to fill the model with
     * @return Model
     */
    public static function fill(Model $model, $data = [], $without = [])
    {
        if (! is_array($data)) {
            $data = [];
        }

        switch (get_class($model)) {
            case "Bedard\Shop\Models\Category": $data = self::getCategoryData($data); break;
            case "Bedard\Shop\Models\Discount": $data = self::getDiscountData($data); break;
            case "Bedard\Shop\Models\Inventory": $data = self::getInventoryData($data); break;
            case "Bedard\Shop\Models\Price": $data = self::getPriceData($data); break;
            case "Bedard\Shop\Models\Product": $data = self::getProductData($data); break;
        }

        $model->fill($data);

        foreach ($without as $key) {
            unset($model->attributes[$key]);
        }

        return $model;
    }

    /**
     * Category.
     *
     * @param  array $data
     * @return array
     */
    public static function getCategoryData(array $data = [])
    {
        $faker = Faker\Factory::create();

        return array_merge([
            'description_html' => '<p>'.Lorem::paragraph().'</p>',
            'is_active' => true,
            'is_visible' => true,
            'name' => $faker->words(3, true),
            'slug' => $faker->slug,
        ], $data);
    }

    /**
     * Discount.
     *
     * @param   array $data
     * @return  array
     */
    public static function getDiscountData(array $data = [])
    {
        $faker = Faker\Factory::create();

        return array_merge([
            'amount_exact' => 0,
            'amount_percentage' => 0,
            'name' => $faker->words(3, true),
            'start_at' => null,
            'end_at' => null,
        ], $data);
    }

    /**
     * Inventory.
     *
     * @param   array $data
     * @return  array
     */
    public static function getInventoryData(array $data = [])
    {
        $faker = Faker\Factory::create();

        return array_merge([
            'product_id' => 0,
            'quantity' => 0,
            'sku' => null,
        ], $data);
    }

    /**
     * Price.
     *
     * @param  array $data
     * @return aray
     */
    public static function getPriceData(array $data = [])
    {
        return array_merge([
            'discount_id' => null,
            'end_at' => null,
            'price' => 0,
            'product_id' => 0,
            'start_at' => null,
        ], $data);
    }

    /**
     * Product.
     *
     * @param  array $data
     * @return array
     */
    public static function getProductData(array $data = [])
    {
        $faker = Faker\Factory::create();

        return array_merge([
            'base_price' => rand(1, 100) + (rand(0, 100) / 100),
            'description_html' => '<p>'.Lorem::paragraph().'</p>',
            'name' => $faker->words(3, true),
            'slug' => $faker->slug,
        ], $data);
    }
}
