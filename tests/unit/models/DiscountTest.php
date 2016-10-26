<?php namespace Bedard\Shop\Tests\Unit\Models;

use Bedard\Shop\Models\Discount;
use Bedard\Shop\Tests\Factory;
use Bedard\Shop\Tests\PluginTestCase;
use Carbon\Carbon;

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
}
