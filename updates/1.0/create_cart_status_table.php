<?php namespace Bedard\Shop\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class CreateCartStatusTable extends Migration
{
    public function up()
    {
        Schema::create('bedard_shop_cart_status', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->string('driver')->nullable();
            $table->integer('cart_id')->unsigned()->nullable();
            $table->integer('status_id')->unsigned()->nullable();
            $table->timestamp('created_at')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bedard_shop_cart_status');
    }
}
