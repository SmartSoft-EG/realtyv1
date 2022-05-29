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
        Schema::create('realty', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('realty_owner_id');
            // $table->foreign('realty_owner_id')->references('id')->on('realty_owners')->onDelete('cascade');
            $table->string('code');
            $table->string('name');
            $table->string('description');
            $table->string('address');
            $table->integer('size')->default(100);
            $table->tinyInteger('floors_count')->default(1);
            // $table->smallInteger('floor_units')->default(1);
            // $table->smallInteger('base_floor_markets')->default(0);
            // $table->smallInteger('base_floor_apartments')->default(0);
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
        Schema::dropIfExists('realty');
    }
};
