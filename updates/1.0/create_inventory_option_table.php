<?php namespace Bedard\Shop\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateInventoryOptionTable extends Migration
{
    public function up()
    {
        Schema::create('bedard_shop_inventory_option', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('inventory_id')->unsigned()->nullable();
            $table->integer('option_id')->unsigned()->index();
            $table->primary(['inventory_id', 'option_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('bedard_shop_inventory_option');
    }
}
