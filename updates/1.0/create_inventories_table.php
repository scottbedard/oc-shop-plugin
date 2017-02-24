<?php namespace Bedard\Shop\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateInventoriesTable extends Migration
{
    public function up()
    {
        Schema::create('bedard_shop_inventories', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bedard_shop_inventories');
    }
}
