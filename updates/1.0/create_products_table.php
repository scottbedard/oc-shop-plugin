<?php namespace Bedard\Shop\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;

class CreateProductsTable extends Migration
{
    use \October\Rain\Database\Traits\Validation;

    public function up()
    {
        Schema::create('bedard_shop_products', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('name')->default('');
            $table->string('slug')->default('')->unique();
            $table->text('description_plain');
            $table->text('description_html');
            $table->decimal('base_price', 10, 2)->default(0)->unsigned();
            $table->boolean('is_enabled')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('bedard_shop_products');
    }
}
