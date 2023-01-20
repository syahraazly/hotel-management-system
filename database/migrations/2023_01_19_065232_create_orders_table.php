<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->integer('order_number');
            $table->text('customer');
            $table->text('customer_email');
            $table->timestamp('order_date');
            $table->date('check_in');
            $table->date('check_out');
            $table->text('guest_name');
            $table->integer('rooms_amount');
            $table->unsignedBigInteger('type_id');
            $table->integer('user_id')->nullable();
            $table->foreign('type_id')->references('type_id')->on('type');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
};
