<?php namespace Bedard\Shop\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateCartItemsTable extends Migration
{
    public function up()
    {
        Schema::create('bedard_shop_cart_items', function(Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('cart_id')->unsigned()->index();
            $table->integer('inventory_id')->unsigned()->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bedard_shop_cart_items');
    }
}
