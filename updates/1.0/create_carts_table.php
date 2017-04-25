<?php namespace Bedard\Shop\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class CreateCartsTable extends Migration
{
    public function up()
    {
        Schema::create('bedard_shop_carts', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('token', 40)->index();
            $table->integer('update_count')->unsigned()->default(0);
            $table->integer('item_count')->unsigned()->default(0);
            $table->decimal('item_total', 10, 2)->unsigned()->default(0);
            $table->string('closed_by')->nullable();
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bedard_shop_carts');
    }
}
