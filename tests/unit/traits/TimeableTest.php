<?php namespace Bedard\Shop\Tests\Unit\Models;

use Bedard\Shop\Models\Price;
use Bedard\Shop\Tests\Factory;
use Bedard\Shop\Tests\PluginTestCase;
use Carbon\Carbon;

class TimeableTest extends PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

    public function test_timeable_scopes()
    {
        // the different permutations of start and end dates
        $u1 = Factory::create(new Price, ['start_at' => Carbon::tomorrow()])->id;
        $u2 = Factory::create(new Price, ['start_at' => Carbon::tomorrow(), 'end_at' => Carbon::tomorrow()->addDays(1)])->id;
        $a1 = Factory::create(new Price)->id;
        $a2 = Factory::create(new Price, ['start_at' => Carbon::yesterday()])->id;
        $a3 = Factory::create(new Price, ['start_at' => Carbon::yesterday(), 'end_at' => Carbon::tomorrow()])->id;
        $a4 = Factory::create(new Price, ['end_at' => Carbon::tomorrow()])->id;
        $e1 = Factory::create(new Price, ['start_at' => Carbon::yesterday()->subDays(1), 'end_at' => Carbon::yesterday()])->id;
        $e2 = Factory::create(new Price, ['end_at' => Carbon::yesterday()])->id;

        // isActive & isNotActive
        $this->assertArrayEquals([$a1, $a2, $a3, $a4], Price::isActive()->lists('id'));
        $this->assertArrayEquals([$u1, $u2, $e1, $e2], Price::isNotActive()->lists('id'));

        // isExpired & isNotExpired
        $this->assertArrayEquals([$e1, $e2], Price::isExpired()->lists('id'));
        $this->assertArrayEquals([$u1, $u2, $a1, $a2, $a3, $a4], Price::isNotExpired()->lists('id'));

        // isUpcoming & isNotUpcoming
        $this->assertArrayEquals([$u1, $u2], Price::isUpcoming()->lists('id'));
        $this->assertArrayEquals([$a1, $a2, $a3, $a4, $e1, $e2], Price::isNotUpcoming()->lists('id'));
    }
}
