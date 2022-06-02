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
            $table->unsignedBigInteger('acc_person_id');
            $table->float('percent')->default(1);
            $table->timestamps();
            $table->foreign('realty_unit_id')->references('id')->on('realty_units')->onDelete('cascade');
            $table->foreign('acc_person_id')->references('id')->on('acc_persons');
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
