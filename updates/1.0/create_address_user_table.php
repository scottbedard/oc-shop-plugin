<?php namespace Bedard\Shop\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class CreateAddressUserTable extends Migration
{
    public function up()
    {
        Schema::create('bedard_shop_address_user', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('address_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->primary(['address_id', 'user_id'], 'address_user');
        });
    }

    public function down()
    {
        Schema::dropIfExists('bedard_shop_address_user');
    }
}
