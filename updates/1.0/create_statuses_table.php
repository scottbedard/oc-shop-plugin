<?php namespace Bedard\Shop\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class CreateStatusesTable extends Migration
{
    public function up()
    {
        Schema::create('bedard_shop_statuses', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->default('')->index();
            $table->string('color')->nullable();
            $table->string('icon')->nullable();
            $table->boolean('is_default')->default(false);
            $table->boolean('is_abandoned')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bedard_shop_statuses');
    }
}
