<?php namespace Bedard\Shop\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class CreateCategoriesTable extends Migration
{
    public function up()
    {
        Schema::create('bedard_shop_categories', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->default('');
            $table->string('slug')->default('')->unique();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bedard_shop_categories');
    }
}
