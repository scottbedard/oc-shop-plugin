<?php namespace Bedard\Shop\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class CreateDriverConfigsTable extends Migration
{
    public function up()
    {
        Schema::create('bedard_shop_driver_configs', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('class')->default('');
            $table->boolean('is_enabled')->default(false);
            $table->json('config');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bedard_shop_driver_configs');
    }
}
