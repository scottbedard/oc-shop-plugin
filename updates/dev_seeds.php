<?php namespace Bedard\Shop\Updates;

use Bedard\Shop\Models\Category;
use Bedard\Shop\Models\Discount;
use Bedard\Shop\Models\Inventory;
use Bedard\Shop\Models\Option;
use Bedard\Shop\Models\OptionValue;
use Bedard\Shop\Models\Product;
use Bedard\Shop\Tests\Factory;
use Carbon\Carbon;
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
        $this->seedOptionsAndInventories();
        $this->seedDiscounts();
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

        Product::syncAllInheritedCategories();
    }

    protected function seedOptionsAndInventories()
    {
        Product::all()->each(function ($product) {
            $option = Factory::create(new Option, [
                'name' => 'Size',
                'placeholder' => '-- select size --',
                'product_id' => $product->id,
            ]);

            $value = Factory::create(new OptionValue, ['option_id' => $option->id, 'name' => 'Small', 'sort_order' => 0]);
            Factory::create(new OptionValue, ['option_id' => $option->id, 'name' => 'Medium', 'sort_order' => 1]);
            Factory::create(new OptionValue, ['option_id' => $option->id, 'name' => 'Large', 'sort_order' => 2]);

            $inventory = Factory::create(new Inventory, [
                'product_id' => $product->id,
                'quantity' => rand(0, 3),
            ]);

            $inventory->optionValues()->sync([$value->id]);
        });
    }

    protected function seedDiscounts()
    {
        Factory::create(new Discount, [
            'name' => 'Expired',
            'end_at' => Carbon::yesterday(),
        ]);

        Factory::create(new Discount, [
            'name' => 'Active',
            'start_at' => Carbon::yesterday(),
            'end_at' => Carbon::tomorrow(),
        ]);

        Factory::create(new Discount, [
            'name' => 'Upcoming',
            'start_at' => Carbon::tomorrow(),
        ]);
    }
}
