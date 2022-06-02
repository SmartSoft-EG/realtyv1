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
        Schema::create('sls_airline_rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('prc_hotel_id');
            $table->unsignedBigInteger('sls_airline_id');
            $table->tinyInteger('rooms_count');
            $table->tinyInteger('extra_beds_count');
            $table->timestamps();
            $table->foreign('prc_hotel_id')->references('id')->on('prc_hotels')->onDelete('cascade');
            $table->foreign('sls_airline_id')->references('id')->on('sls_airlines')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sls_airline_rooms');
    }
};
