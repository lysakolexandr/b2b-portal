<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePriceCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_categories', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->timestamps();
            $table->bigInteger('price_id')->nullable();
            $table->bigInteger('category_id')->nullable();
            $table->integer('check')->nullable()->default(0);
            $table->bigInteger('brand')->nullable();
            $table->bigInteger('brand_category_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('price_categories');
    }
}
