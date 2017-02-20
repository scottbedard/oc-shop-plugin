<?php namespace Bedard\Shop;

use Faker;
use Model;

class Factory
{
    /**
     * Create a model and save it to the database.
     *
     * @param  Model    $model  Model to create
     * @param  array    $omit   Data to fill model with
     * @return Model
     */
    public static function create(Model $model, array $data = [], array $omit = [])
    {
        $model = self::fill($model, $data, $omit);
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
    public static function fill(Model $model, array $data = [], array $omit = [])
    {
        switch (get_class($model)) {
            case "Bedard\Shop\Models\Category": $data = self::getCategoryData($data); break;
            case "Bedard\Shop\Models\Product":  $data = self::getProductData($data); break;
        }

        $model->fill($data);

        foreach ($omit as $key) {
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
            'name' => $faker->words(3, true),
            'slug' => $faker->slug,
        ], $data);
    }

    /**
     * Category.
     *
     * @param  array $data
     * @return array
     */
    public static function getProductData(array $data = [])
    {
        $faker = Faker\Factory::create();

        return array_merge([
            'name' => $faker->words(3, true),
            'slug' => $faker->slug,
        ], $data);
    }
}
