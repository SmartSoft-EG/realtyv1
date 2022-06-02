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
        Schema::create('prc_hotels', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('hotel_id');
            $table->unsignedBigInteger('prc_airline_id');
            $table->string('state', 40)->default('active');
            $table->float('room_price');
            $table->float('extra_bed_price');
            $table->float('sweet_room_price');
            $table->timestamps();
            $table->foreign('supplier_id')->references('id')->on('acc_persons')->onDelete('cascade');
            $table->foreign('hotel_id')->references('id')->on('hotels')->onDelete('cascade');
            $table->foreign('prc_airline_id')->references('id')->on('prc_airlines')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('prc_hotels');
    }
};
