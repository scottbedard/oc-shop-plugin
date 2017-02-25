<?php namespace Bedard\Shop\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class CreatePricesTable extends Migration
{
    public function up()
    {
        Schema::create('bedard_shop_prices', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->decimal('price', 10, 2)->unsigned();
            $table->integer('product_id')->unsigned()->index();
            $table->integer('discount_id')->unsigned()->nullable()->index();
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bedard_shop_prices');
    }
}
