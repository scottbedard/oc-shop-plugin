<?php namespace Bedard\Shop\Updates;

use Schema;
use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;

class CreatePromotionsTable extends Migration
{
    public function up()
    {
        Schema::create('bedard_shop_promotions', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->default('');
            $table->string('message')->default('');
            $table->decimal('minimum_cart_value', 10, 2)->unsigned()->default(0);
            $table->decimal('amount', 10, 2)->unsigned()->default(0);
            $table->boolean('is_percentage')->default(false);
            $table->timestamp('start_at')->nullable();
            $table->timestamp('end_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bedard_shop_promotions');
    }
}
