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
            $table->bigIncrements('order_id');
            $table->bigInteger('order_number');
            $table->text('customer_name');
            $table->text('customer_email');
            $table->timestamp('order_date');
            $table->date('check_in');
            $table->date('check_out');
            $table->text('guest_name');
            $table->integer('rooms_amount');
            $table->integer('user_id')->nullable();
            $table->unsignedBigInteger('type_id');
            $table->foreign('type_id')->references('type_id')->on('type');
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
        Schema::dropIfExists('orders');
    }
};
