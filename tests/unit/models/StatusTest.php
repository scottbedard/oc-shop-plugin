<?php namespace Bedard\Shop\Tests\Unit\Models;

use Bedard\Shop\Classes\Factory;
use Bedard\Shop\Models\Status;
use Bedard\Shop\Tests\Unit\ShopTestCase;
use October\Rain\Database\ModelException;

class StatusTest extends ShopTestCase
{
    public function test_creating_multiple_abandoned_statuses()
    {
        $one = Factory::create(new Status, ['is_abandoned' => true]);
        $two = Factory::create(new Status, ['is_abandoned' => true]);

        $this->assertFalse(Status::find($one->id)->is_abandoned);
    }

    public function test_creating_multiple_default_statuses()
    {
        $one = Factory::create(new Status, ['is_default' => true]);
        $two = Factory::create(new Status, ['is_default' => true]);

        $this->assertFalse(Status::find($one->id)->is_default);
    }

    public function test_abandoned_status_cannot_be_deleted()
    {
        $one = Factory::create(new Status, ['is_abandoned' => true]);
        $one->delete();

        $two = Factory::create(new Status, ['is_abandoned' => false]);
        $two->delete();

        $this->assertTrue(Status::whereId($one->id)->exists());
        $this->assertFalse(Status::whereId($two->id)->exists());
    }

    public function test_default_status_cannot_be_deleted()
    {
        $one = Factory::create(new Status, ['is_default' => true]);
        $one->delete();

        $two = Factory::create(new Status, ['is_default' => false]);
        $two->delete();

        $this->assertTrue(Status::whereId($one->id)->exists());
        $this->assertFalse(Status::whereId($two->id)->exists());
    }

    public function test_a_status_cannot_be_both_default_and_abandoned()
    {
        $foo = Factory::fill(new Status, [
            'is_abandoned' => true,
            'is_default' => true,
        ]);

        $this->setExpectedException(ModelException::class);
        $foo->save();
    }

    public function test_the_only_default_status_cannot_be_turned_off()
    {
        $foo = Factory::create(new Status, ['is_default' => true]);
        $foo->is_default = false;

        $this->setExpectedException(ModelException::class);
        $foo->save();
    }
}
