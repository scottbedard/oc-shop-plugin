<?php namespace Bedard\Shop\Tests\Unit;

use Bedard\Shop\Tests\PluginTestCase;

class AssertionsTest extends PluginTestCase
{
    public function test_assertArrayEquals()
    {
        $this->assertArrayEquals([1, 2], [2, 1]);
        $this->assertArrayEquals([0 => 'a', 1 => 'b'], [0 => 'b', 1 => 'a']);
    }
}
