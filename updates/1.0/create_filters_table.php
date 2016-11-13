<?php namespace Bedard\Shop\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateFiltersTable extends Migration
{
    public function up()
    {
        Schema::create('bedard_shop_filters', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('category_id')->unsigned()->index();
            $table->integer('sort_order')->unsigned()->default(0);
            $table->string('left')->default('');
            $table->string('comparator')->default('');
            $table->string('right')->default('');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bedard_shop_filters');
    }
}
