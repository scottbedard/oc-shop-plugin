<?php namespace Bedard\Shop\Updates;

use Bedard\Shop\Models\Category;
use Bedard\Shop\Tests\Factory;
use October\Rain\Database\Updates\Seeder;

class DevSeeder extends Seeder
{
    public function run()
    {
        // only run the seeder in development
        if (app()->env !== 'dev') return;

        $this->seedCategories();
    }

    protected function seedCategories()
    {
        $clothes = Factory::create(new Category, [ 'name' => 'Clothes', 'slug' => 'clothes' ]);
        Factory::create(new Category, [ 'name' => 'Shirts', 'slug' => 'shirts', 'parent_id' => $clothes->id ]);
        Factory::create(new Category, [ 'name' => 'Pants', 'slug' => 'pants', 'parent_id' => $clothes->id ]);
        Factory::create(new Category, [ 'name' => 'Shoes', 'slug' => 'shoes', 'parent_id' => $clothes->id ]);
        $electronics = Factory::create(new Category, [ 'name' => 'Electronics', 'slug' => 'electronics' ]);
        Factory::create(new Category, [ 'name' => 'Phones', 'slug' => 'phones', 'parent_id' => $electronics->id ]);
        $computers = Factory::create(new Category, [ 'name' => 'Computers', 'slug' => 'computers', 'parent_id' => $electronics->id ]);
        Factory::create(new Category, [ 'name' => 'Mac', 'slug' => 'mac', 'parent_id' => $computers->id ]);
        Factory::create(new Category, [ 'name' => 'PC', 'slug' => 'pc', 'parent_id' => $computers->id ]);
        Factory::create(new Category, [ 'name' => 'Televisions', 'slug' => 'televisions', 'parent_id' => $electronics->id ]);
    }
}
