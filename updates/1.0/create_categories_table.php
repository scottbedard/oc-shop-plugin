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
            $table->integer('parent_id')->nullable()->unsigned()->index();
            $table->integer('nest_left')->nullable()->unsigned()->index();
            $table->integer('nest_right')->nullable()->unsigned()->index();
            $table->integer('nest_depth')->nullable()->unsigned()->index();
            $table->string('name')->default('');
            $table->string('slug')->default('')->unique();
            $table->text('description_plain');
            $table->text('description_html');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bedard_shop_categories');
    }
}
