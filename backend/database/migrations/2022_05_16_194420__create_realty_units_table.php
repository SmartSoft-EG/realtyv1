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
        Schema::create('realty_units', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('realty_id');
            $table->foreign('realty_id')->references('id')->on('realty');
            $table->string('code');
            $table->string('type')->default('apartment');
            $table->string('name');
            $table->tinyInteger('floor')->default(0);
            $table->string('description');
            $table->string('state')->default('free');
            $table->float('price')->nullable();
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
        Schema::dropIfExists('realty_units');
    }
};
