<?php namespace Bedard\Shop\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class CreateCategoryProductTables extends Migration
{
    public function up()
    {
        Schema::create('bedard_shop_category_product', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('category_id')->unsigned()->nullable();
            $table->integer('product_id')->unsigned()->nullable();
            $table->primary(['category_id', 'product_id'], 'category_product');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bedard_shop_category_product');
    }
}
