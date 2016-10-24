<?php namespace Bedard\Shop\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreateCategoryProductTables extends Migration
{
    public function up()
    {
        Schema::create('bedard_shop_category_product', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('category_id')->unsigned()->nullable();
            $table->integer('product_id')->unsigned()->nullable();
            $table->boolean('is_inherited')->default(false);
            $table->primary(['category_id', 'product_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('bedard_shop_category_product');
    }
}
