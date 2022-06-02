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
        Schema::create('sls_airline_persons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('person_id');
            $table->unsignedBigInteger('sls_airline_id');
            $table->date('birth_date');
            $table->timestamps();
            $table->foreign('person_id')->references('id')->on('acc_persons')->onDelete('cascade');
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
        Schema::dropIfExists('sls_airline_persons');
    }
};
