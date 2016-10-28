<?php namespace Bedard\Shop\Tests\Unit\Models;

use Bedard\Shop\Models\Category;
use Bedard\Shop\Models\Discount;
use Bedard\Shop\Models\Price;
use Bedard\Shop\Models\Product;
use Bedard\Shop\Tests\Factory;
use Bedard\Shop\Tests\PluginTestCase;
use Carbon\Carbon;
use stdClass;

class DiscountTest extends PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

    public function test_discount_name_is_required()
    {
        $this->setExpectedException(\October\Rain\Database\ModelException::class);
        Factory::fill(new Discount, null, ['name'])->validate();
    }

    public function test_start_date_must_be_before_end_date()
    {
        $this->setExpectedException(\October\Rain\Database\ModelException::class);
        Factory::fill(new Discount, ['start_at' => Carbon::tomorrow(), 'end_at' => Carbon::yesterday()])->validate();
    }

    public function test_setting_discount_amounts()
    {
        $exact = Factory::create(new Discount, ['amount_exact' => 1, 'amount_percentage' => 2, 'is_percentage' => false]);
        $this->assertEquals(1, $exact->amount);

        $percentage = Factory::create(new Discount, ['amount_exact' => 1, 'amount_percentage' => 2, 'is_percentage' => true]);
        $this->assertEquals(2, $percentage->amount);
    }

    public function test_filtering_discount_form_fields()
    {
        $discount = Factory::fill(new Discount, ['is_percentage' => false]);

        $fields = new stdClass;
        $fields->amount_exact = new stdClass;
        $fields->amount_percentage = new stdClass;
        $fields->amount_exact->hidden = null;
        $fields->amount_percentage->hidden = null;

        $discount->filterFields($fields);
        $this->assertFalse($fields->amount_exact->hidden);
        $this->assertTrue($fields->amount_percentage->hidden);

        $discount->is_percentage = true;
        $discount->filterFields($fields);
        $this->assertTrue($fields->amount_exact->hidden);
        $this->assertFalse($fields->amount_percentage->hidden);
    }

    public function test_selectStatus_scope()
    {
        $expired = Factory::create(new Discount, ['end_at' => Carbon::yesterday()]);
        $active = Factory::create(new Discount);
        $upcoming = Factory::create(new Discount, ['start_at' => Carbon::tomorrow()]);

        $discounts = Discount::selectStatus()->get();
        $this->assertEquals(0, $discounts->where('id', $expired->id)->first()->status);
        $this->assertEquals(1, $discounts->where('id', $active->id)->first()->status);
        $this->assertEquals(2, $discounts->where('id', $upcoming->id)->first()->status);
    }

    public function test_isExpired_and_isNotExpired_scopes()
    {
        $expired = Factory::create(new Discount, ['end_at' => Carbon::yesterday()]);
        $active = Factory::create(new Discount);
        $upcoming = Factory::create(new Discount, ['start_at' => Carbon::tomorrow()]);

        $isExpired = Discount::isExpired()->get();
        $this->assertEquals(1, $isExpired->count());
        $this->assertEquals($expired->id, $isExpired->first()->id);

        $isNotExpired = Discount::isNotExpired()->get();
        $this->assertEquals(2, $isNotExpired->count());
        $this->assertArrayEquals([$active->id, $upcoming->id], $isNotExpired->lists('id'));
    }

    public function test_prices_are_created_when_discount_is_saved()
    {
        $category1 = Factory::create(new Category);
        $product1 = Factory::create(new Product);
        $product1->categories()->sync([$category1->id]);
        $product2 = Factory::create(new Product);
        $product3 = Factory::create(new Product);

        $discount = Factory::create(new Discount, [
            'start_at' => Carbon::yesterday(),
            'end_at' => Carbon::tomorrow(),
        ]);

        $discount->categories()->sync([$category1->id]);
        $discount->products()->sync([$product2->id]);

        $discount->save();
        $this->assertArrayEquals([$product1->id, $product2->id], $discount->prices()->lists('product_id'));

        foreach ($discount->prices()->with('product')->get() as $price) {
            $this->assertEquals($discount->start_at, $price->start_at);
            $this->assertEquals($discount->end_at, $price->end_at);
            $this->assertEquals($discount->calculatePrice($price->product->base_price), $price->price);
        }
    }

    public function test_calculating_discounted_prices()
    {
        $discount = Factory::fill(new Discount, ['amount' => 10, 'is_percentage' => false]);
        $this->assertEquals(0, $discount->calculatePrice(5));
        $this->assertEquals(40, $discount->calculatePrice(50));
        $discount->is_percentage = true;
        $this->assertEquals(45, $discount->calculatePrice(50));
    }

    public function test_syncing_all_discount_prices()
    {
        $product1 = Factory::create(new Product);
        $category1 = Factory::create(new Category);
        $category2 = Factory::create(new Category, ['parent_id' => $category1->id]);
        $product2 = Factory::create(new Product);
        $product2->categories()->sync([$category2->id]);
        Product::syncAllInheritedCategories();

        $discount1 = Factory::create(new Discount);
        $discount2 = Factory::create(new Discount);
        $discount1->products()->sync([$product1->id]);
        $discount1->categories()->sync([$category1->id]);
        $discount2->products()->sync([$product2->id]);

        Discount::syncAllPrices();
        $this->assertEquals(1, Price::whereProductId($product1->id)->whereDiscountId($discount1->id)->count());
        $this->assertEquals(1, Price::whereProductId($product2->id)->whereDiscountId($discount1->id)->count());
        $this->assertEquals(1, Price::whereProductId($product2->id)->whereDiscountId($discount2->id)->count());
    }

    public function test_deleting_a_discount_also_deletes_its_prices()
    {
        $product = Factory::create(new Product);
        $discount = Factory::create(new Discount);
        $discount->products()->sync([$product->id]);
        Discount::syncAllPrices();

        $this->assertEquals(1, Price::whereDiscountId($discount->id)->count());
        $discount->delete();
        $this->assertEquals(0, Price::whereDiscountId($discount->id)->count());
    }
}
