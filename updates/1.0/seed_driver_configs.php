<?php namespace Bedard\Shop\Updates;

use Bedard\Shop\Models\DriverConfig;
use October\Rain\Database\Updates\Seeder;

class SeedDriverConfigsTable extends Seeder
{
    public function run()
    {
        $nopayment = DriverConfig::firstOrNew(['driver' => 'Bedard\Shop\Drivers\NoPayment']);
        $nopayment->config = ['event_complete' => 5];
        $nopayment->save();
    }
}
