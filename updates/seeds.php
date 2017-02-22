<?php namespace Bedard\Shop\Updates;

use Bedard\Shop\Models\Product;
use Bedard\Shop\Classes\Factory;
use Bedard\Shop\Models\Category;
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
