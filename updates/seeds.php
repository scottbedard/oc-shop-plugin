<?php namespace Bedard\Shop\Updates;

use Bedard\Shop\Classes\Factory;
use Bedard\Shop\Models\Category;
use Bedard\Shop\Models\Option;
use Bedard\Shop\Models\Product;
use October\Rain\Database\Updates\Seeder;

class Seeds extends Seeder
{
    public function run()
    {
        // only run the seeder in development
        if (app()->env !== 'dev') {
            return;
        }

        echo "\n";
        echo '  Seeding test data...';
        echo "\n";

        $this->seedCategories(10);
        $this->seedProducts(20);
        $this->seedOptions();

        echo "\n  Done.\n";
        echo "\n";
    }

    protected function seedCategories($quantity)
    {
        for ($i = 0; $i < $quantity; $i++) {
            Factory::create(new Category);
        }

        echo "  - Categories\n";
    }

    protected function seedOptions()
    {
        Product::all()->each(function ($product) {
            Factory::create(new Option, [
                'product_id' => $product->id,
                'name' => 'Size',
                'placeholder' => '-- select size --',
                'value_data' => [
                    ['id' => null, 'name' => 'Small', 'sort_order' => 0],
                    ['id' => null, 'name' => 'Medium', 'sort_order' => 1],
                    ['id' => null, 'name' => 'Large', 'sort_order' => 2],
                ],
            ]);

            Factory::create(new Option, [
                'product_id' => $product->id,
                'name' => 'Color',
                'placeholder' => '-- select color --',
                'value_data' => [
                    ['id' => null, 'name' => 'Blue', 'sort_order' => 2],
                    ['id' => null, 'name' => 'Green', 'sort_order' => 1],
                    ['id' => null, 'name' => 'Orange', 'sort_order' => 2],
                    ['id' => null, 'name' => 'Purple', 'sort_order' => 2],
                    ['id' => null, 'name' => 'Red', 'sort_order' => 0],
                ],
            ]);
        });

        echo "  - Options\n";
    }

    protected function seedProducts($quantity)
    {
        $categories = Category::all();

        for ($i = 0; $i < $quantity; $i++) {
            $product = Factory::create(new Product);
            $product->categories()->attach($categories->random(1));
        }

        echo "  - Products\n";
    }
}
