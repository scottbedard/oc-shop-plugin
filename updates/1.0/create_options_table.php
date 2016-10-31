<?php namespace Bedard\Shop\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateOptionsTable extends Migration
{
    public function up()
    {
        Schema::create('bedard_shop_options', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('product_id')->unsigned()->index();
            $table->string('name')->default('');
            $table->string('placeholder')->default('');
            $table->integer('sort_order')->unsigned()->default(0);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bedard_shop_options');
    }
}
