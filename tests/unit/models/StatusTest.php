<?php namespace Bedard\Shop\Tests\Unit\Models;

use Bedard\Shop\Classes\Factory;
use Bedard\Shop\Models\Status;
use Bedard\Shop\Tests\Unit\ShopTestCase;

class StatusTest extends ShopTestCase
{
    public function test_creating_multiple_default_statuses()
    {
        $one = Factory::create(new Status, ['is_default' => true]);
        $two = Factory::create(new Status, ['is_default' => true]);

        $this->assertFalse(Status::find($one->id)->is_default);
    }

    public function test_default_statuses_cannot_be_deleted()
    {
        $one = Factory::create(new Status, ['is_default' => true]);
        $one->delete();

        $two = Factory::create(new Status, ['is_default' => false]);
        $two->delete();

        $this->assertTrue(Status::whereId($one->id)->exists());
        $this->assertFalse(Status::whereId($two->id)->exists());
    }
}
