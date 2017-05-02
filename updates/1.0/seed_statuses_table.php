<?php namespace Bedard\Shop\Updates;

use Bedard\Shop\Models\Status;
use Lang;
use October\Rain\Database\Updates\Seeder;

class SeedStatusesTable extends Seeder
{
    public function run()
    {
        Status::create([
            'name' => Lang::get('bedard.shop::lang.statuses.presets.open'),
            'icon' => 'icon-shopping-cart',
            'is_default' => true,
        ]);

        Status::create([
            'name' => Lang::get('bedard.shop::lang.statuses.presets.awaiting_payment'),
            'icon' => 'icon-spinner',
        ]);

        Status::create([
            'name' => Lang::get('bedard.shop::lang.statuses.presets.abandoned'),
            'color' => '#c0392b',
            'icon' => 'icon-times',
            'is_abandoned' => true,
        ]);

        Status::create([
            'name' => Lang::get('bedard.shop::lang.statuses.presets.payment_received'),
            'icon' => 'icon-money',
            'is_reducing' => true,
        ]);

        Status::create([
            'name' => Lang::get('bedard.shop::lang.statuses.presets.complete'),
            'color' => '#27ae60',
            'icon' => 'icon-check',
        ]);
    }
}
