<?php namespace Bedard\Shop\Tests\Backend\Models;

use Bedard\Shop\Models\DriverConfig;
use Bedard\Shop\Tests\Backend\ShopTestCase;

class DriverConfigTest extends ShopTestCase
{
    public function test_is_enabled_scope()
    {
        $enabled = DriverConfig::create(['is_enabled' => true]);
        $disabled = DriverConfig::create(['is_enabled' => false]);

        $query = DriverConfig::isEnabled();

        $this->assertEquals(1, $query->count());
        $this->assertEquals($enabled->id, $query->first()->id);
    }
}
