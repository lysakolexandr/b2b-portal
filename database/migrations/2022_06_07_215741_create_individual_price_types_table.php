<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIndividualPriceTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('individual_price_types', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->integer('product_id')->nullable();
            $table->integer('price_type_id')->nullable();
            $table->integer('customer_id')->nullable();

            $table->index(['product_id', 'customer_id'], 'product_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('individual_price_types');
    }
}
