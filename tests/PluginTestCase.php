<?php namespace Bedard\Shop\Tests;

use PluginTestCase as BasePluginTestCase;

class PluginTestCase extends BasePluginTestCase
{
    /**
     * Assert that two arrays have the same set of values.
     *
     * @param  array    $expected
     * @param  array    $actual
     * @param  string   $message
     * @return void
     */
    protected function assertArrayEquals(array $expected, array $actual, $message = '')
    {
        sort($expected);
        sort($actual);

        $this->assertEquals($expected, $actual, $message);
    }
}
