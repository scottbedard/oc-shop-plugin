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
            $table->integer('parent_id')->unsigned()->nullable()->index();
            $table->integer('sort_order')->default(0)->unsigned();
            $table->string('name')->default('');
            $table->string('slug')->default('')->unique();
            $table->text('description_plain');
            $table->text('description_html');
            $table->integer('product_columns')->unsigned()->default(0);
            $table->integer('product_rows')->unsigned()->default(0);
            $table->string('product_sort_column', 16)->nullable();
            $table->string('product_sort_direction', 4)->nullable();
            $table->text('product_order');
            $table->boolean('is_active')->default(false);
            $table->boolean('is_visible')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bedard_shop_categories');
    }
}
