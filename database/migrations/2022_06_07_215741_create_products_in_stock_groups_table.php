<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsInStockGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_in_stock_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->default(0)->primary();
            $table->unsignedBigInteger('product_id')->default(0)->index('product_id');
            $table->unsignedBigInteger('stock_group_id')->default(0)->index('stock_group_id');
            $table->unsignedBigInteger('qty')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products_in_stock_groups');
    }
}
