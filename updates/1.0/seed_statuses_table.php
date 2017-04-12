<?php namespace Bedard\Shop\Updates;

use Bedard\Shop\Models\Status;
use October\Rain\Database\Updates\Seeder;

class SeedStatusesTable extends Seeder
{
    public function run()
    {
        Status::create([
            'name' => 'bedard.shop::lang.statuses.open',
            'icon' => 'icon-shopping-cart',
        ]);

        Status::create([
            'name' => 'bedard.shop::lang.statuses.awaiting_payment',
            'icon' => 'icon-spinner',
        ]);

        Status::create([
            'name' => 'bedard.shop::lang.statuses.abandoned',
            'color' => '#c0392b',
            'icon' => 'icon-times',
        ]);

        Status::create([
            'name' => 'bedard.shop::lang.statuses.payment_received',
            'icon' => 'icon-money',
        ]);

        Status::create([
            'name' => 'bedard.shop::lang.statuses.complete',
            'color' => '#27ae60',
            'icon' => 'icon-check',
        ]);
    }
}
