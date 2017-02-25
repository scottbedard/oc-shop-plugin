<?php namespace Bedard\Shop\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class CreateDiscountProductTables extends Migration
{
    public function up()
    {
        Schema::create('bedard_shop_discount_product', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('discount_id')->unsigned()->nullable();
            $table->integer('product_id')->unsigned()->nullable();
            $table->primary(['discount_id', 'product_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('bedard_shop_discount_product');
    }
}
