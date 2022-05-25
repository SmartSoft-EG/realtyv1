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
        Schema::create('realty_units_owners', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('realty_unit_id');
            $table->unsignedBigInteger('owner_id');
            $table->foreign('realty_unit_id')->references('id')->on('realty_units')->onDelete('cascade');
            $table->foreign('owner_id')->references('id')->on('owners')->onDelete('cascade');
            $table->float('percent')->default(1);
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
        Schema::dropIfExists('realty_units_owners');
    }
};
