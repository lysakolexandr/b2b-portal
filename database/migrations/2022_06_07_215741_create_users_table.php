<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_code')->nullable();
            $table->string('name', 255);
            $table->string('email', 255)->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password', 255);
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->integer('selected_contract_id')->nullable()->default(0);
            $table->integer('customer_code')->nullable();
            $table->char('pass', 50)->nullable();
            $table->integer('active')->nullable()->default(1);
            $table->integer('stock_group_id')->nullable();
            $table->integer('trusted')->nullable()->default(0);
            $table->integer('price_view')->nullable()->default(1);
            $table->integer('settlements_view')->nullable()->default(1);
            $table->bigInteger('parent_id')->nullable();
            $table->char('api_token', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
