<?php namespace Bedard\Shop\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class CreateAddressesTable extends Migration
{
    public function up()
    {
        Schema::create('bedard_shop_addresses', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('customer_id')->unsigned()->nullable()->index();
            $table->boolean('is_primary')->default(false);
            $table->boolean('is_billing')->default(false);
            $table->boolean('is_shipping')->default(false);
            $table->string('line_1')->default('');
            $table->string('line_2')->default('');
            $table->string('line_3')->default('');
            $table->string('city')->default('');
            $table->string('province')->default('');
            $table->string('postcode')->default('');
            $table->string('country')->default('');
            $table->text('additional_data');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bedard_shop_addresses');
    }
}
