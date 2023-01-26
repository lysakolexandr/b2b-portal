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
            $table->bigIncrements('id');
            $table->timestamps();
            $table->string('name', 255);
            $table->string('name_ru', 255)->nullable();
            $table->longText('description')->nullable();
            $table->longText('description_ru')->nullable();
            $table->string('code', 255)->nullable();
            $table->integer('pack_qty')->nullable();
            $table->integer('pack_qty2')->nullable();
            $table->integer('active')->nullable();
            $table->integer('display_balances')->nullable()->default(10);
            $table->integer('brand_id')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('currency_id')->nullable();
            $table->string('image', 255)->nullable();
            $table->double('price', 10, 2)->nullable();
            $table->integer('country_of_consignment_id')->nullable();
            $table->integer('country_of_brand_registration_id')->nullable();
            $table->longText('properties')->nullable();
            $table->longText('properties_filter')->nullable();
            $table->longText('properties_ru')->nullable();
            $table->longText('properties_filter_ru')->nullable();
            $table->integer('multiplicity')->nullable()->default(1);
            $table->string('multiplicity_unit', 50)->nullable();
            $table->string('status_code', 50)->nullable();
            $table->char('status', 100)->nullable();
            $table->char('status_ru', 100)->nullable();
            $table->string('barcode', 100)->nullable();
            $table->string('supplier_code', 50)->nullable();
            $table->string('supplier_name', 250)->nullable();
            $table->string('supplier_barcode', 100)->nullable();
            $table->decimal('weight', 20, 6)->nullable();
            $table->decimal('volume', 20, 6)->nullable();
            $table->string('article', 50)->nullable();

            $table->fullText(['name', 'code', 'name_ru'], 'name');
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
