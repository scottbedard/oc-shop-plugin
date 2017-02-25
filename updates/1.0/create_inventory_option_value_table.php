<?php namespace Bedard\Shop\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateInventoryOptionValueTable extends Migration
{
    public function up()
    {
        Schema::create('bedard_shop_inventory_option_value', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('inventory_id')->unsigned();
            $table->integer('option_value_id')->unsigned();
            $table->primary(['inventory_id', 'option_value_id'], 'inventory_option_value_ids');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bedard_shop_inventory_option_value');
    }
}
