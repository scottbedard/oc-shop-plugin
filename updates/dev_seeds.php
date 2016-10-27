<?php namespace Bedard\Shop\Updates;

use Bedard\Shop\Models\Category;
use Bedard\Shop\Models\Product;
use Bedard\Shop\Tests\Factory;
use October\Rain\Database\Updates\Seeder;

class DevSeeder extends Seeder
{
    public function run()
    {
        // only run the seeder in development
        if (app()->env !== 'dev') {
            return;
        }

        $this->seedCategories();
        $this->seedProducts(10);
    }

    protected function seedCategories()
    {
        $parent1 = Factory::create(new Category, ['name' => 'Parent 1', 'slug' => 'parent-1']);
        $parent2 = Factory::create(new Category, ['name' => 'Parent 2', 'slug' => 'parent-2']);
        Factory::create(new Category, ['name' => 'Child 1', 'slug' => 'child-1', 'parent_id' => $parent1->id]);
        Factory::create(new Category, ['name' => 'Child 2', 'slug' => 'child-2', 'parent_id' => $parent2->id]);
    }

    protected function seedProducts($count)
    {
        $categories = Category::count();

        for ($i = 0; $i < $count; $i++) {
            $product = Factory::create(new Product, ['name' => 'Product '.$i, 'slug' => 'product-'.$i]);
            $product->categories()->sync([rand(1, $categories)]);
        }
    }
}
