<?php namespace Bedard\Shop\Classes;

use Faker;
use Model;
use Faker\Provider\Lorem;

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
        $seedData = [];

        switch (get_class($model)) {
            case 'Bedard\Shop\Models\Category': $seedData = self::getCategoryData($data); break;
            case 'Bedard\Shop\Models\Option':   $seedData = self::getOptionData($data); break;
            case 'Bedard\Shop\Models\Product':  $seedData = self::getProductData($data); break;
        }

        $model->fill(array_merge($seedData, $data));

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

        return [
            'name' => $faker->words(2, true),
            'slug' => $faker->slug,
        ];
    }

    /**
     * Option.
     *
     * @param  array $data
     * @return array
     */
    public static function getOptionData(array $data = [])
    {
        $faker = Faker\Factory::create();

        return [
            'name' => $faker->words(2, true),
            'placeholder' => $faker->words(3, true),
            'sort_order' => 0,
            'value_data' => [
                ['_key' => 1, 'id' => null, 'name' => 'a', 'sort_order' => 0],
            ],
        ];
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

        return [
            'base_price' => rand(1, 100) + (rand(0, 100) / 100),
            'description_html' => '<p>'.Lorem::paragraph().'</p>',
            'name' => $faker->words(2, true),
            'slug' => $faker->slug,
        ];
    }
}
