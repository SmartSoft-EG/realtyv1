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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id;
            $table->unsignedBigInteger('acc_num');
            $table->string('name')->unique();
            $table->string('type');
            $table->smallInteger('level');
            $table->string('status',10)->nullable();
            $table->unsignedBigInteger('parent_id');
            $table->foreign('parent_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->float('depit');
            $table->float('credit');
            $table->morphs('accountable');
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
        Schema::dropIfExists('accounts');
    }
};
