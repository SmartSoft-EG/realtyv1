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
        Schema::create('sls_airlines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->unsignedBigInteger('prc_airline_id')->nullable();
            $table->unsignedBigInteger('prc_hotel_id')->nullable();

            $table->Integer('seats_count');
            $table->string('state', 40)->default('active');
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('acc_persons')->onDelete('cascade');
            $table->foreign('prc_airline_id')->references('id')->on('prc_airlines')->onDelete('cascade');
            $table->foreign('prc_hotel_id')->references('id')->on('prc_hotels')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sls_airlines');
    }
};
