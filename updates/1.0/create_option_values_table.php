<?php namespace Bedard\Shop\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateOptionValuesTable extends Migration
{
    public function up()
    {
        Schema::create('bedard_shop_option_values', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->default('');
            $table->integer('sort_order')->unsigned()->default(0);
            $table->integer('option_id')->unsigned()->nullable()->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bedard_shop_option_values');
    }
}
