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

        $this->seedCategories();
        $this->seedProducts();
        $this->seedOptions();
        $this->seedInventories();
        $this->attachProductsToCategories();

        echo "\n  Done.\n";
        echo "\n";
    }

    protected function attachProductsToCategories()
    {
        Product::whereSlug('no-inventories')
            ->firstOrFail()
            ->categories()
            ->attach(Category::whereSlug('parent')->firstOrFail());

        Product::whereSlug('free-thing')
            ->firstOrFail()
            ->categories()
            ->attach(Category::whereSlug('child-a')->firstOrFail());

        Product::whereSlug('out-of-stock')
            ->firstOrFail()
            ->categories()
            ->attach(Category::whereSlug('child-b')->firstOrFail());

        Product::whereSlug('shirt')
            ->firstOrFail()
            ->categories()
            ->attach(Category::whereSlug('grandchild')->firstOrFail());

        Product::syncAllCategories();

        echo "  - Attaching products to categories\n";
    }

    protected function seedCategories()
    {
        $parent = Factory::create(new Category, [
            'name' => 'Parent',
            'slug' => 'parent',
        ]);

        $childA = Factory::create(new Category, [
            'name' => 'Child A',
            'slug' => 'child-a',
        ]);

        $childB = Factory::create(new Category, [
            'name' => 'Child B',
            'slug' => 'child-b',
        ]);

        $grandchild = Factory::create(new Category, [
            'name' => 'Grandchild',
            'slug' => 'grandchild',
        ]);

        $childA->makeChildOf($parent);
        $childB->makeChildOf($parent);
        $grandchild->makeChildOf($childA);

        echo "  - Categories\n";
    }

    protected function seedInventories()
    {
        // out of stock
        Factory::create(new Inventory, [
            'product_id' => Product::whereSlug('out-of-stock')->firstOrFail()->id,
            'quantity' => 0,
        ]);

        // free
        Factory::create(new Inventory, [
            'product_id' => Product::whereSlug('free-thing')->firstOrFail()->id,
            'quantity' => 1,
        ]);

        // shirt
        Factory::create(new Inventory, [
            'product_id' => Product::whereSlug('shirt')->firstOrFail()->id,
            'quantity' => 5,
            'value_ids' => [1, 4],
        ]);

        Factory::create(new Inventory, [
            'product_id' => Product::whereSlug('shirt')->firstOrFail()->id,
            'quantity' => 5,
            'value_ids' => [1, 5],
        ]);

        Factory::create(new Inventory, [
            'product_id' => Product::whereSlug('shirt')->firstOrFail()->id,
            'quantity' => 0,
            'value_ids' => [1, 6],
        ]);

        echo "  - Inventories\n";
    }

    protected function seedOptions()
    {
        $shirt = Product::whereSlug('shirt')->firstOrFail();

        $size = Factory::create(new Option, [
            'name' => 'Size',
            'placeholder' => '-- select size --',
            'product_id' => $shirt->id,
            'value_data' => [
                ['id' => null, 'name' => 'Small', 'sort_order' => 0],
                ['id' => null, 'name' => 'Medium', 'sort_order' => 1],
                ['id' => null, 'name' => 'Large', 'sort_order' => 2],
            ],
        ]);

        $size = Factory::create(new Option, [
            'name' => 'Color',
            'placeholder' => '-- select color --',
            'product_id' => $shirt->id,
            'value_data' => [
                ['id' => null, 'name' => 'Red', 'sort_order' => 0],
                ['id' => null, 'name' => 'Green', 'sort_order' => 1],
                ['id' => null, 'name' => 'Blue', 'sort_order' => 2],
            ],
        ]);

        echo "  - Options\n";
    }

    protected function seedProducts()
    {
        $noInventories = Factory::create(new Product, [
            'description_html' => '<p>This product is out of stock because it had no inventories</p>',
            'name' => 'No inventories',
            'slug' => 'no-inventories',
        ]);

        $outOfStock = Factory::create(new Product, [
            'description_html' => '<p>This product is out of stock</p>',
            'name' => 'Out of stock',
            'slug' => 'out-of-stock',
        ]);

        $free = Factory::create(new Product, [
            'base_price' => 0,
            'description_html' => '<p>This product tests a base price of zero</p>',
            'name' => 'Free thing',
            'slug' => 'free-thing',
        ]);

        $shirt = Factory::create(new Product, [
            'base_price' => 19.99,
            'description_html' => '<p>This product tests multiple inventories</p>',
            'name' => 'Shirt',
            'slug' => 'shirt',
        ]);

        echo "  - Products\n";
    }
}
