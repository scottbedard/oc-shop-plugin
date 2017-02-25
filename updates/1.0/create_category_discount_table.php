<?php namespace Bedard\Shop\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class CreateCategoryDiscountTables extends Migration
{
    public function up()
    {
        Schema::create('bedard_shop_category_discount', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('category_id')->unsigned()->nullable();
            $table->integer('discount_id')->unsigned()->nullable();
            $table->primary(['category_id', 'discount_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('bedard_shop_category_discount');
    }
}
