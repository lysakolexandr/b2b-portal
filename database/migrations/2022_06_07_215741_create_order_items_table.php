<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->integer('id', true)->index('id');
            $table->integer('product_id')->default(0);
            $table->decimal('qty', 20, 6)->default(0);
            $table->integer('order_id')->default(0);
            $table->decimal('price', 20, 6)->nullable()->default(0);
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->integer('currency')->nullable()->default(980);
            $table->decimal('shipped', 10, 0)->default(0);
 
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_items');
    }
}
