<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->timestamps();
            $table->integer('customer_id')->default(0);
            $table->string('manager_name', 255)->nullable();
            $table->string('manager_phone', 255)->nullable();
            $table->string('manager_email', 255)->nullable();
            $table->string('manager_viber', 255)->nullable();
            $table->string('manager_telegram', 255)->nullable();
            $table->string('language', 255)->nullable()->default('ua');
            $table->bigInteger('price_type_id')->nullable();
            $table->integer('stock_group_id')->nullable();
            $table->integer('hide_price')->nullable()->default(0);
            $table->integer('sort')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_settings');
    }
}
