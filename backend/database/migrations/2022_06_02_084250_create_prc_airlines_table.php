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
        Schema::create('prc_airlines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('airline_id');
            $table->dateTime('departure_time');
            $table->Integer('seats_count');
            $table->string('state', 40)->default('active');
            $table->float('adult_seat_price');
            $table->float('child_seat_price');
            $table->float('return_seat_price');
            $table->string('pnr');
            $table->timestamps();
            $table->foreign('supplier_id')->references('id')->on('acc_persons')->onDelete('cascade');
            $table->foreign('airline_id')->references('id')->on('airlines')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prc_airlines');
    }
};
