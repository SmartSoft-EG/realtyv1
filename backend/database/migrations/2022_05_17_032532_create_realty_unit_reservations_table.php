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
        Schema::create('realty_unit_reservations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('realty_unit_id');
            $table->unsignedBigInteger('customer_id');
            $table->date('start_date');
            $table->date('end_date');
            $table->float('price');
            $table->float('total_price');
            $table->float('commission')->default(0);
            $table->string('state', 40)->default('active');
            $table->foreign('realty_unit_id')->references('id')->on('realty_units')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('realty_unit_reservations');
    }
};
