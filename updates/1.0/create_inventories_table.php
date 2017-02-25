<?php namespace Bedard\Shop\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class CreateInventoriesTable extends Migration
{
    public function up()
    {
        Schema::create('bedard_shop_inventories', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('product_id')->unsigned()->index();
            $table->string('sku')->nullable()->unique();
            $table->integer('quantity')->unsigned()->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bedard_shop_inventories');
    }
}
