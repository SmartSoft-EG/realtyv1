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
            $table->unsignedBigInteger('parent_id');
            $table->foreign('parent_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->morphs('accountable');
            $table->string('name')->unique();
            $table->string('type', 20);
            $table->smallInteger('level');
            $table->string('status', 10)->default('active');
            $table->boolean('is_main')->default(0);
            $table->float('debit')->default(0);
            $table->float('credit')->default(0);;
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
