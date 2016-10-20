<?php namespace Bedard\Shop\Tests;

use Exception;
use Faker;
use Model;

class Factory
{
    /**
     * Create a model and save it to the database
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
     * Create a model and fill it with data
     *
     * @param  Model    $model  Model to fill
     * @param  array    $data   Data to fill the model with
     * @return Model
     */
    public static function fill(Model $model, $data = [], $without = [])
    {
        if (!is_array($data)) {
            $data = [];
        }

        switch (get_class($model)) {
            case "Bedard\Shop\Models\Product": $data = self::getProductData($data);
        }

        $model->fill($data);

        foreach ($without as $key) {
            unset($model->attributes[$key]);
        }

        return $model;
    }

    /**
     * Get product data
     *
     * @param  array $data
     * @return array
     */
    public static function getProductData(array $data = []) {
        $faker = Faker\Factory::create();

        return array_merge([
            'name' => $faker->words(3, true),
            'price' => rand(1, 100) + (rand(0, 100) / 100),
            'slug' => $faker->slug,
        ], $data);
    }
}
