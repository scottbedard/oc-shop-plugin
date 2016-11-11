<?php namespace Bedard\Shop\Tests\Unit\Models;

use Bedard\Shop\Models\Category;
use Bedard\Shop\Models\Discount;
use Bedard\Shop\Models\Inventory;
use Bedard\Shop\Models\Option;
use Bedard\Shop\Models\OptionValue;
use Bedard\Shop\Models\Price;
use Bedard\Shop\Models\Product;
use Bedard\Shop\Tests\Factory;
use Bedard\Shop\Tests\PluginTestCase;
use Carbon\Carbon;

class ProductTest extends PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

    public function test_product_name_is_required()
    {
        $this->setExpectedException(\October\Rain\Database\ModelException::class);
        Factory::fill(new Product, null, ['name'])->validate();
    }

    public function test_product_base_price_is_required()
    {
        $this->setExpectedException(\October\Rain\Database\ModelException::class);
        Factory::fill(new Product, null, ['base_price'])->validate();
    }

    public function test_product_base_price_must_be_a_positive_number()
    {
        $this->setExpectedException(\October\Rain\Database\ModelException::class);
        Factory::fill(new Product, ['base_price' => -1])->validate();
    }

    public function test_product_slug_is_required()
    {
        $this->setExpectedException(\October\Rain\Database\ModelException::class);
        Factory::fill(new Product, null, ['slug'])->validate();
    }

    public function test_product_slug_must_be_unique()
    {
        $this->setExpectedException(\October\Rain\Database\ModelException::class);
        $product = Factory::create(new Product);
        Factory::create(new Product, ['slug' => $product->slug]);
    }

    public function test_aliases_the_category_relationship_for_a_checkboxlist()
    {
        $cat1 = Factory::create(new Category, ['name' => 'cat1']);
        $cat2 = Factory::create(new Category, ['name' => 'cat2']);
        $product = Factory::create(new Product);

        $this->assertArrayEquals([], $product->categoriesList);

        $this->assertArrayEquals([
            $cat1->id => $cat1->name,
            $cat2->id => $cat2->name,
        ], $product->getCategoriesListOptions());

        $product->categoriesList = [$cat1->id];
        $product->save();

        $this->assertArrayEquals([$cat1->id], $product->categoriesList);
    }

    public function test_saving_a_product_sets_inherited_category_relationships()
    {
        $clothes = Factory::create(new Category);
        $shirts = Factory::create(new Category, ['parent_id' => $clothes->id]);
        $shirt = Factory::fill(new Product);
        $shirt->categoriesList = [$shirts->id];
        $shirt->save();

        $this->assertArrayEquals([$clothes->id, $shirts->id], Category::whereHas('products', function ($product) use ($shirt) {
            return $product->where('id', $shirt->id);
        })->lists('id'));
    }

    public function test_products_sync_their_inherited_categories()
    {
        $parent1 = Factory::create(new Category);
        $parent2 = Factory::create(new Category);
        $child = Factory::create(new Category, ['parent_id' => $parent1->id]);

        $product = Factory::create(new Product);
        $product->categories()->sync([$child->id]);
        $product->syncInheritedCategories();

        $this->assertTrue($parent1->products()->where('id', $product->id)->exists());
        $this->assertFalse($parent2->products()->where('id', $product->id)->exists());

        $child->parent_id = $parent2->id;
        $child->save();
        $product->syncInheritedCategories();

        $this->assertFalse($parent1->products()->where('id', $product->id)->exists());
        $this->assertTrue($parent2->products()->where('id', $product->id)->exists());
    }

    public function test_syncing_all_products_with_inherited_categories()
    {
        $parent1 = Factory::create(new Category);
        $parent2 = Factory::create(new Category);
        $child1 = Factory::create(new Category, ['parent_id' => $parent1->id]);
        $child2 = Factory::create(new Category, ['parent_id' => $parent2->id]);
        $product1 = Factory::create(new Product);
        $product2 = Factory::create(new Product);
        $product1->categories()->sync([$child1->id]);
        $product2->categories()->sync([$child2->id]);

        Product::syncAllInheritedCategories();

        $this->assertTrue($parent1->products()->where('id', $product1->id)->exists());
        $this->assertTrue($parent2->products()->where('id', $product2->id)->exists());
    }

    public function test_products_save_their_base_price()
    {
        $product = Factory::create(new Product);
        $this->assertEquals(1, Price::where('product_id', $product->id)->where('price', $product->base_price)->count());
    }

    public function test_current_price_relationship()
    {
        $product = Factory::create(new Product, ['base_price' => 5]);
        Factory::create(new Price, ['product_id' => $product->id, 'price' => 1, 'start_at' => Carbon::tomorrow()]);
        Factory::create(new Price, ['product_id' => $product->id, 'price' => 2]);
        Factory::create(new Price, ['product_id' => $product->id, 'price' => 3]);

        $product->load('current_price');
        $this->assertEquals(2, $product->current_price->price);
    }

    public function test_join_current_price_scope()
    {
        $product = Factory::create(new Product, ['base_price' => 5]);
        Factory::create(new Price, ['product_id' => $product->id, 'price' => 1, 'start_at' => Carbon::tomorrow()]);
        Factory::create(new Price, ['product_id' => $product->id, 'price' => 2]);
        Factory::create(new Price, ['product_id' => $product->id, 'price' => 3]);

        $result = Product::joinPrice()->find($product->id);
        $this->assertEquals(2, $result->price);
    }

    public function test_creating_product_where_discount_should_apply()
    {
        $category1 = Factory::create(new Category);
        $category2 = Factory::create(new Category, ['parent_id' => $category1->id]);
        $discount = Factory::create(new Discount, ['amount' => 15, 'is_percentage' => false]);
        $discount->categories()->sync([$category2->id]);
        $product = Factory::create(new Product, ['categoriesList' => [$category2->id]]);
        $this->assertEquals(1, Price::whereProductId($product->id)->whereDiscountId($discount->id)->count());
    }

    public function test_moving_to_a_discounted_category_recalculates_price()
    {
        $category1 = Factory::create(new Category);
        $category2 = Factory::create(new Category);
        $discount = Factory::create(new Discount, ['amount' => 15, 'is_percentage' => false]);
        $discount->categories()->sync([$category2->id]);
        $product = Factory::create(new Product, ['categoriesList' => [$category1->id]]);

        $this->assertEquals(0, Price::whereProductId($product->id)->whereDiscountId($discount->id)->count());
        $product->categoriesList = [$category2->id];
        $product->save();
        $this->assertEquals(1, Price::whereProductId($product->id)->whereDiscountId($discount->id)->count());
    }

    public function test_moving_out_of_a_discounted_category_recalculates_price()
    {
        $category1 = Factory::create(new Category);
        $category2 = Factory::create(new Category);
        $discount = Factory::create(new Discount);
        $discount->categories()->sync([$category1->id]);
        $product = Factory::create(new Product, ['categoriesList' => [$category1->id]]);

        $this->assertEquals(1, Price::whereProductId($product->id)->whereDiscountId($discount->id)->count());
        $product->categoriesList = [$category2->id];
        $product->save();
        $this->assertEquals(0, Price::whereProductId($product->id)->whereDiscountId($discount->id)->count());
    }

    public function test_changing_price_recalculates_discounts()
    {
        $product = Factory::create(new Product, ['base_price' => 100]);
        $discount = Factory::create(new Discount, ['amount_exact' => 15, 'is_percentage' => false]);
        $discount->products()->sync([$product->id]);
        $discount->save();
        $this->assertEquals(1, Price::whereProductId($product->id)->whereDiscountId($discount->id)->wherePrice(85)->count());
        $product->base_price = 50;
        $product->save();
        $this->assertEquals(0, Price::whereProductId($product->id)->whereDiscountId($discount->id)->wherePrice(85)->count());
        $this->assertEquals(1, Price::whereProductId($product->id)->whereDiscountId($discount->id)->wherePrice(35)->count());
    }

    public function test_saving_a_product_with_duplicate_option()
    {
        $this->setExpectedException(\October\Rain\Database\ModelException::class);

        Factory::fill(new Product, [
            'optionsInventories' => [
                'options' => [
                    ['name' => 'Foo ', 'is_deleted' => false],
                    ['name' => ' foo', 'is_deleted' => false],
                ],
                'inventories' => [],
            ],
        ])->validate();
    }

    public function test_saving_a_product_with_options_and_inventories()
    {
        $data = [
            'optionsInventories' => [
                'options' => [
                    [
                        'id' => null,
                        'is_deleted' => false,
                        'name' => 'Size',
                        'placeholder' => 'Select size',
                        'values' => [
                            [
                                'id' => null,
                                'is_deleted' => false,
                                'name' => 'Small',
                            ],
                            [
                                'id' => null,
                                'is_deleted' => false,
                                'name' => 'Large',
                            ],
                        ],
                    ],
                ],
                'inventories' => [],
            ],
        ];

        $product = Factory::create(new Product, $data);

        // create the options
        $product->load('options.values');
        $option = $product->options->first();
        $values = $option->values;
        $this->assertEquals(1, $product->options->count());
        $this->assertEquals('Size', $option->name);
        $this->assertEquals('Select size', $option->placeholder);
        $this->assertEquals(2, $values->count());
        $this->assertEquals('Small', $values->first()->name);
        $this->assertEquals('Large', $values->last()->name);

        // save them
        $data['optionsInventories']['options'][0]['id'] = $option->id;
        $data['optionsInventories']['options'][0]['values'][0]['id'] = $values->first()->id;
        $data['optionsInventories']['options'][0]['values'][1]['id'] = $values->last()->id;
        $data['optionsInventories']['options'][0]['name'] = 'foo';
        $data['optionsInventories']['options'][0]['values'][0]['name'] = 'bar';
        $product->fill($data);
        $product->save();
        $this->assertEquals('foo', $product->options()->first()->name);
        $this->assertEquals('bar', $product->options()->first()->values()->first()->name);

        // delete the option
        $data['optionsInventories']['options'][0]['is_deleted'] = true;
        $product->fill($data);
        $product->save();
        $this->assertEquals(0, $product->options()->count());
    }

    public function test_inventories_must_have_unique_values()
    {
        Factory::fill(new Product, [
            'optionsInventories' => [
                'options' => [],
                'inventories' => [
                    ['valueIds' => [1, 2]],
                    ['valueIds' => [1, 2, 3]],
                ],
            ],
        ])->validate();

        $this->setExpectedException(\October\Rain\Database\ModelException::class);
        Factory::fill(new Product, [
            'optionsInventories' => [
                'options' => [],
                'inventories' => [
                    ['is_deleted' => false, 'valueIds' => [1, 2, 3]],
                    ['is_deleted' => false, 'valueIds' => [2, 3, 1]],
                ],
            ],
        ])->validate();
    }

    public function test_inventories_can_be_deleted()
    {
        $inventory = Factory::create(new Inventory);

        Factory::create(new Product, [
            'optionsInventories' => [
                'options' => [],
                'inventories' => [
                    [
                        'id' => $inventory->id,
                        'is_deleted' => true,
                        'valueIds' => [],
                    ],
                ],
            ],
        ]);

        $this->assertEquals(false, Inventory::where('id', $inventory->id)->exists());
    }

    public function test_deleting_an_option_deletes_associated_inventories()
    {
        $product = Factory::create(new Product);
        $option1 = Factory::create(new Option, ['product_id' => $product->id]);
        $option2 = Factory::create(new Option, ['product_id' => $product->id]);
        $optionValue1 = Factory::create(new OptionValue, ['option_id' => $option1->id]);
        $optionValue2 = Factory::create(new OptionValue, ['option_id' => $option2->id]);
        $inventory1 = Factory::create(new Inventory);
        $inventory2 = Factory::create(new Inventory);
        $inventory1->optionValues()->sync([$optionValue1->id]);
        $inventory2->optionValues()->sync([$optionValue2->id]);

        $product->optionsInventories = [
            'options' => [
                [
                    'id' => $option1->id,
                    'is_deleted' => false,
                    'name' => 'Whatever',
                    'values' => [
                        [
                            'id' => $optionValue1->id,
                            'is_deleted' => true,
                            'name' => 'Whatever',
                        ],
                    ],
                ],
                [
                    'id' => $option2->id,
                    'is_deleted' => true,
                    'name' => 'foo',
                    'values' => [
                        [
                            'id' => $optionValue2->id,
                            'is_deleted' => false,
                            'name' => 'bar',
                        ],
                    ],
                ],
            ],
            'inventories' => [
                [
                    'id' => $inventory1->id,
                    'is_deleted' => false,
                    'valueIds' => [$optionValue1->id],
                ],
                [
                    'id' => $inventory2->id,
                    'is_deleted' => false,
                    'valueIds' => [$optionValue2->id],
                ],
            ],
        ];

        $product->save();
        $this->assertEquals(false, Inventory::whereId($inventory1->id)->exists());
        $this->assertEquals(false, Inventory::whereId($inventory2->id)->exists());
    }

    public function test_joinInventory_scope()
    {
        $outOfStock = Factory::create(new Product);
        $inStock = Factory::create(new Product);
        $inventory = Factory::create(new Inventory, ['quantity' => 1, 'product_id' => $inStock->id]);

        $products = Product::joinInventory()->select('*')->orderBy('id')->get()->toArray();
        $this->assertEquals(null, $products[0]['inventory']);
        $this->assertEquals(1, $products[1]['inventory']);
    }

    public function test_selectStatus_scope()
    {
        $disabled = Factory::create(new Product, ['is_enabled' => false]);
        $outOfStock = Factory::create(new Product);
        $inStock = Factory::create(new Product);
        $discounted = Factory::create(new Product, ['base_price' => 50]);
        $inventory = Factory::create(new Inventory, ['quantity' => 1, 'product_id' => $inStock->id]);
        $inventory = Factory::create(new Inventory, ['quantity' => 1, 'product_id' => $discounted->id]);
        $discount = Factory::create(new Discount, ['amount_exact' => 15, 'is_percentage' => false]);
        $discount->products()->sync([$discounted->id]);
        $discount->save();

        $products = Product::joinPrice()->joinInventory()->selectStatus()->orderBy('id')->get()->toArray();
        $this->assertEquals(-2, $products[0]['status']);
        $this->assertEquals(-1, $products[1]['status']);
        $this->assertEquals(0, $products[2]['status']);
        $this->assertEquals(1, $products[3]['status']);
    }
}
