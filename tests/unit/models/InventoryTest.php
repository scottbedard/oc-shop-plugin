<?php namespace Bedard\Shop\Tests\Unit\Models;

use PluginTestCase;
use Bedard\Shop\Classes\Factory;
use Bedard\Shop\Models\Inventory;
use October\Rain\Exception\ValidationException;

class InventoryTest extends PluginTestCase
{
    protected $refreshPlugins = ['Bedard.Shop'];

    public function test_sku_must_be_unique()
    {
        // null sku's should not collide
        Factory::create(new Inventory, ['sku' => null]);
        Factory::create(new Inventory, ['sku' => null]);

        // defined sku's should
        Factory::create(new Inventory, ['sku' => 'foo']);
        $this->setExpectedException(ValidationException::class);
        Factory::create(new Inventory, ['sku' => 'foo']);
    }
}
