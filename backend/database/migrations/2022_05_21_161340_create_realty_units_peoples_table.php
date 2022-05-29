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
        Schema::create('realty_units_peoples', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('realty_unit_id');
            $table->unsignedBigInteger('realty_units_people_id');
            $table->float('percent')->default(1);
            $table->timestamps();
            $table->foreign('realty_unit_id')->references('id')->on('realty_units')->onDelete('cascade');
            $table->foreign('realty_units_people_id')->references('id')->on('peoples')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('realty_units_owners');
    }
};
