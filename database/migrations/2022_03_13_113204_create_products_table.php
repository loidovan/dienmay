<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            $table->string('name');
            $table->bigInteger("category_id")->unsigned();
            $table->bigInteger("brand_id")->unsigned();
            $table->bigInteger("type_id")->unsigned();
            $table->string('description',1000);
            $table->string('image');
            $table->decimal('price',10,0);
            $table->integer('warranty');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
