<?php namespace Bedard\Shop\Tests\Backend\Models;

use Bedard\Shop\Classes\Factory;
use Bedard\Shop\Models\Category;
use Bedard\Shop\Models\Inventory;
use Bedard\Shop\Models\Option;
use Bedard\Shop\Models\Product;
use Bedard\Shop\Tests\Backend\ShopTestCase;
use DB;

class ProductTest extends ShopTestCase
{
    public function test_getting_formatted_price()
    {
        $product = new Product;
        $product->base_price = 1.2;
        $this->assertEquals('1.20', $product->formattedPrice());
    }

    public function test_products_can_belong_to_categories()
    {
        $category = Factory::create(new Category);
        $product = Factory::create(new Product);
        $product->categories()->attach($category);
        $this->assertEquals($category->id, $product->categories()->find($category->id)->id);
    }

    public function test_setting_plain_description()
    {
        $product = Factory::create(new Product, ['description_html' => '<b>Hello</b>']);
        $this->assertEquals('Hello', $product->description_plain);
    }

    public function test_selecting_status()
    {
        $disabled = Factory::create(new Product, ['is_enabled' => false]);
        $enabled = Factory::create(new Product, ['is_enabled' => true]);
        $products = Product::selectStatus()->get();
        $this->assertEquals(0, $products->find($disabled->id)->status);
        $this->assertEquals(1, $products->find($enabled->id)->status);
    }

    //
    // options
    //
    public function test_creating_an_option()
    {
        $product = Factory::create(new Product, [
            'options_inventories' => '{
                "inventories": [],
                "options": [
                    {
                        "_delete": false,
                        "_key": 2,
                        "id": null,
                        "name": "size",
                        "placeholder": "select size",
                        "sort_order": 1,
                        "values": [
                            {
                                "_delete": false,
                                "_key": 3,
                                "id": null,
                                "name": "small",
                                "sort_order": 2
                            }
                        ]
                    }
                ]
            }',
        ]);

        $product->load('options.values');

        // make sure the option exists
        $this->assertEquals('size', $product->options[0]['name']);
        $this->assertEquals('select size', $product->options[0]['placeholder']);

        // make sure the values exist
        $this->assertEquals('small', $product->options[0]['values'][0]['name']);
    }

    public function test_saving_an_option()
    {
        $product = Factory::create(new Product, [
            'options_inventories' => '{
                "inventories": [],
                "options": [
                    {
                        "_delete": false,
                        "_key": 2,
                        "id": null,
                        "name": "size",
                        "placeholder": "select size",
                        "sort_order": 1,
                        "values": [
                            {
                                "_delete": false,
                                "_key": 3,
                                "id": null,
                                "name": "small",
                                "sort_order": 2
                            }
                        ]
                    }
                ]
            }',
        ]);

        $product->options_inventories = '{
            "inventories": [],
            "options": [
                {
                    "_delete": false,
                    "_key": 2,
                    "id": 1,
                    "name": "updated size",
                    "placeholder": "updated select size",
                    "sort_order": 2,
                    "values": [
                        {
                            "_delete": false,
                            "_key": 3,
                            "id": 1,
                            "name": "updated small",
                            "sort_order": 3
                        }
                    ]
                }
            ]
        }';

        $product->save();
        $product->load('options.values');

        // check the updated option
        $this->assertEquals(1, $product->options[0]['id']);
        $this->assertEquals('updated size', $product->options[0]['name']);
        $this->assertEquals('updated select size', $product->options[0]['placeholder']);

        // check the updated option value
        $this->assertEquals(1, $product->options[0]['values'][0]['id']);
        $this->assertEquals('updated small', $product->options[0]['values'][0]['name']);
    }

    public function test_deleting_an_option()
    {
        $product = Factory::create(new Product, [
            'options_inventories' => '{
                "inventories": [],
                "options": [
                    {
                        "_delete": false,
                        "_key": 2,
                        "id": null,
                        "name": "size",
                        "placeholder": "select size",
                        "sort_order": 1,
                        "values": []
                    }
                ]
            }',
        ]);

        $product->options_inventories = '{
            "inventories": [],
            "options": [
                {
                    "_delete": true,
                    "_key": 2,
                    "id": 1,
                    "name": "size",
                    "placeholder": "select size",
                    "sort_order": 1,
                    "values": []
                }
            ]
        }';

        $product->save();
        $product->load('options');

        $this->assertEquals(0, count($product->options));
    }

    public function test_deleting_an_option_value()
    {
        $product = Factory::create(new Product, [
            'options_inventories' => '{
                "inventories": [],
                "options": [
                    {
                        "_delete": false,
                        "_key": 2,
                        "id": null,
                        "name": "size",
                        "placeholder": "select size",
                        "sort_order": 1,
                        "values": [
                            {
                                "_delete": false,
                                "_key": 3,
                                "id": null,
                                "name": "small",
                                "sort_order": 2
                            }
                        ]
                    }
                ]
            }',
        ]);

        $product->options_inventories = '{
            "inventories": [],
            "options": [
                {
                    "_delete": false,
                    "_key": 2,
                    "id": 1,
                    "name": "size",
                    "placeholder": "select size",
                    "sort_order": 1,
                    "values": [
                        {
                            "_delete": true,
                            "_key": 3,
                            "id": 1,
                            "name": "small",
                            "sort_order": 2
                        }
                    ]
                }
            ]
        }';

        $product->save();
        $product->load('options.values');

        $this->assertEquals(0, count($product->options[0]['values']));
    }

    public function test_reorering_options()
    {
        $product = Factory::create(new Product, [
            'options_inventories' => '{
                "inventories": [],
                "options": [
                    {
                        "_delete": false,
                        "_key": 0,
                        "id": null,
                        "name": "foo",
                        "placeholder": "",
                        "sort_order": 0,
                        "values": []
                    },
                    {
                        "_delete": false,
                        "_key": 1,
                        "id": null,
                        "name": "bar",
                        "placeholder": "",
                        "sort_order": 1,
                        "values": []
                    }
                ]
            }',
        ]);

        $product->load('options');
        $this->assertEquals('foo', $product->options[0]['name']);
        $this->assertEquals('bar', $product->options[1]['name']);

        $product->options_inventories = '{
            "inventories": [],
            "options": [
                {
                    "_delete": false,
                    "_key": 1,
                    "id": 2,
                    "name": "bar",
                    "placeholder": "",
                    "sort_order": 1,
                    "values": []
                },
                {
                    "_delete": false,
                    "_key": 0,
                    "id": 1,
                    "name": "foo",
                    "placeholder": "",
                    "sort_order": 0,
                    "values": []
                }
            ]
        }';

        $product->save();
        $product->load('options.values');
        $this->assertEquals('bar', $product->options[0]['name']);
        $this->assertEquals('foo', $product->options[1]['name']);
    }

    public function test_reordering_option_values()
    {
        $product = Factory::create(new Product, [
            'options_inventories' => '{
                "inventories": [],
                "options": [
                    {
                        "_delete": false,
                        "_key": 0,
                        "id": null,
                        "name": "foo",
                        "placeholder": "",
                        "sort_order": 0,
                        "values": [
                            {
                                "_delete": false,
                                "_key": 1,
                                "id": null,
                                "name": "one",
                                "sort_order": 0
                            },
                            {
                                "_delete": false,
                                "_key": 2,
                                "id": null,
                                "name": "two",
                                "sort_order": 1
                            }
                        ]
                    }
                ]
            }',
        ]);

        $product->load('options.values');
        $this->assertEquals('one', $product->options[0]['values'][0]['name']);
        $this->assertEquals('two', $product->options[0]['values'][1]['name']);

        $product->options_inventories = '{
            "inventories": [],
            "options": [
                {
                    "_delete": false,
                    "_key": 0,
                    "id": 1,
                    "name": "foo",
                    "placeholder": "",
                    "sort_order": 0,
                    "values": [
                        {
                            "_delete": false,
                            "_key": 2,
                            "id": 2,
                            "name": "two",
                            "sort_order": 1
                        },
                        {
                            "_delete": false,
                            "_key": 1,
                            "id": 1,
                            "name": "one",
                            "sort_order": 0
                        }
                    ]
                }
            ]
        }';

        $product->save();
        $product->load('options.values');
        $this->assertEquals('two', $product->options[0]['values'][0]['name']);
        $this->assertEquals('one', $product->options[0]['values'][1]['name']);
    }

    public function test_saving_an_inventory()
    {
        $product = Factory::create(new Product, [
            'options_inventories' => '{
              "inventories": [
                {
                  "_delete": false,
                  "_key": 12,
                  "id": null,
                  "quantity": 321,
                  "sku": "abc123",
                  "value_keys": []
                }
              ],
              "options": []
            }',
        ]);

        $product->load('options.values', 'inventories.values');

        $this->assertEquals(1, count($product->inventories));
        $this->assertEquals('abc123', $product->inventories[0]['sku']);
        $this->assertEquals(321, $product->inventories[0]['quantity']);
    }

    // public function test_saving_options()
    // {
    //     $option = Factory::create(new Option);
    //     $option->load('values');
    //     $optionData = $option->toArray();
    //     $optionData['value_data'] = $optionData['values'];
    //     unset($optionData['values']);
    //
    //     $product = Factory::create(new Product, [
    //         'options_inventories' => json_encode([
    //             'inventories' => [],
    //             'options' => [$optionData],
    //         ]),
    //     ]);
    //
    //     $this->assertEquals(1, $product->options()->count());
    //     $this->assertEquals($option->id, $product->options()->first()->id);
    // }

    // public function test_deleting_options()
    // {
    //     $option = Factory::create(new Option);
    //     $option->load('values');
    //     $optionData = $option->toArray();
    //     $optionData['value_data'] = $optionData['values'];
    //     unset($optionData['values']);
    //
    //     $product = Factory::create(new Product, [
    //         'options_inventories' => json_encode([
    //             'inventories' => [],
    //             'options' => [$optionData],
    //         ]),
    //     ]);
    //
    //     $product->options_inventories = json_encode([
    //         'inventories' => [],
    //         'options' => [
    //             [
    //                 '_deleted' => true,
    //                 'id' => $option->id,
    //                 'name' => $option->name,
    //                 'value_data' => [['id' => null, 'name' => 'a']],
    //             ],
    //         ],
    //     ]);
    //
    //     $product->save();
    //     $this->assertEquals(0, $product->options()->count());
    // }

    // public function test_saving_default_inventory()
    // {
    //     $inventory = Factory::create(new Inventory);
    //     $inventoryData = $inventory->toArray();
    //     $inventoryData['value_ids'] = [];
    //
    //     $product = Factory::create(new Product, [
    //         'options_inventories' => json_encode([
    //             'inventories' => [$inventoryData],
    //             'options' => [],
    //         ]),
    //     ]);
    //
    //     $this->assertEquals(1, $product->inventories()->count());
    //     $this->assertEquals($inventory->id, $product->inventories()->first()->id);
    // }

    public function test_is_enabled_scope()
    {
        $enabled = Factory::create(new Product, ['is_enabled' => true]);
        $disabled = Factory::create(new Product, ['is_enabled' => false]);

        $query = Product::isEnabled();
        $this->assertEquals(1, $query->count());
        $this->assertEquals($enabled->id, $query->first()->id);
    }

    public function test_product_saves_its_ancestor_categories()
    {
        $product = Factory::create(new Product);
        $grandparent = Factory::create(new Category);
        $parent = Factory::create(new Category, ['parent_id' => $grandparent->id]);
        $child = Factory::create(new Category, ['parent_id' => $parent->id]);

        $product->categories_field = [$child->id];
        $product->save();

        $direct = DB::table('bedard_shop_category_product')
            ->select('category_id')
            ->whereIsInherited(false)
            ->lists('category_id');

        $inherited = DB::table('bedard_shop_category_product')
            ->select('category_id')
            ->whereIsInherited(true)
            ->lists('category_id');

        $this->assertEquals([$child->id], $direct);
        $this->assertEquals([$grandparent->id, $parent->id], $inherited);
    }

    public function test_adding_a_parent_category()
    {
        $product = Factory::create(new Product);
        $parent = Factory::create(new Category);
        $child = Factory::create(new Category, ['parent_id' => $parent->id]);

        $product->categories_field = [$parent->id];
        $product->save();

        $direct = DB::table('bedard_shop_category_product')
            ->select('category_id')
            ->whereIsInherited(false)
            ->lists('category_id');

        $inherited = DB::table('bedard_shop_category_product')
            ->select('category_id')
            ->whereIsInherited(true)
            ->lists('category_id');

        $this->assertEquals([$parent->id], $direct);
        $this->assertEquals([], $inherited);
    }

    public function test_moving_a_product_from_child_to_parent()
    {
        $product = Factory::create(new Product);
        $parent = Factory::create(new Category);
        $child = Factory::create(new Category, ['parent_id' => $parent->id]);

        $product->categories_field = [$child->id];
        $product->save();

        $product->categories_field = [$parent->id];
        $product->save();

        $direct = DB::table('bedard_shop_category_product')
            ->select('category_id')
            ->whereIsInherited(false)
            ->lists('category_id');

        $inherited = DB::table('bedard_shop_category_product')
            ->select('category_id')
            ->whereIsInherited(true)
            ->lists('category_id');

        $this->assertEquals([$parent->id], $direct);
        $this->assertEquals([], $inherited);
    }

    public function test_promoting_from_inherited_to_direct()
    {
        $product = Factory::create(new Product);
        $parent = Factory::create(new Category);
        $child = Factory::create(new Category, ['parent_id' => $parent->id]);

        $product->categories_field = [$child->id];
        $product->save();

        $product->categories_field = [$child->id, $parent->id];
        $product->save();

        $direct = DB::table('bedard_shop_category_product')
            ->select('category_id')
            ->whereIsInherited(false)
            ->lists('category_id');

        $inherited = DB::table('bedard_shop_category_product')
            ->select('category_id')
            ->whereIsInherited(true)
            ->lists('category_id');

        $this->assertEquals([$child->id, $parent->id], $direct);
        $this->assertEquals([], $inherited);
    }

    public function test_syncing_all_products()
    {
        $foo = Factory::create(new Category);
        $bar = Factory::create(new Category);
        $baz = Factory::create(new Category);
        $bar->makeChildOf($foo);
        $baz->makeChildOf($bar);

        $product = Factory::create(new Product);
        $product->categories_field = [$baz->id];
        $product->save();

        $baz->makeChildOf($foo);
        Product::syncAllCategories();

        $direct = DB::table('bedard_shop_category_product')
            ->select('category_id')
            ->whereIsInherited(false)
            ->lists('category_id');

        $inherited = DB::table('bedard_shop_category_product')
            ->select('category_id')
            ->whereIsInherited(true)
            ->lists('category_id');

        $this->assertEquals([$baz->id], $direct);
        $this->assertEquals([$foo->id], $inherited);
    }
}
