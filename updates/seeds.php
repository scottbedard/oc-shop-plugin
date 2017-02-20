<?php namespace Bedard\Shop\Updates;

use Bedard\Shop\Factory;
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
        echo "  Seeding test data...";
        echo "\n";

        $this->seedCategories(10);

        echo "\n  Done.\n";
        echo "\n";
    }

    protected function seedCategories($quantity = 10)
    {
        for ($i = 0; $i < $quantity; $i++) {
            Factory::create(new Category);
        }

        echo "  - Categories\n";
    }
}
