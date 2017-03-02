<?php namespace Bedard\Shop\Updates;

use Bedard\Shop\Classes\Factory;
use Bedard\Shop\Models\Category;
use Bedard\Shop\Models\Inventory;
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

        $this->seedCategories(5);
        $this->seedProducts(5);
        $this->seedOptions();
        $this->seedInventories();

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

    protected function seedInventories()
    {
        Product::with('options.values')->get()->each(function ($product) {
            // create a default inventory
            Factory::create(new Inventory, ['product_id' => $product->id, 'quantity' => rand(0, 5)]);

            // create an inventory for each of our options
            $product->options->each(function ($option) use ($product) {
                $option->values->each(function ($value) use ($product) {
                    Factory::create(new Inventory, [
                        'product_id' => $product->id,
                        'quantity' => rand(0, 5),
                    ])->values()->attach($value);
                });
            });
        });

        echo "  - Inventories\n";
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
