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
        Schema::create('acc_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_origin_id');
            $table->unsignedBigInteger('acc_person_id')->nullable();
            $table->string('name')->unique();
            $table->string('type', 20)->default('debit');
            $table->float('debit')->default(0);
            $table->float('credit')->default(0);
            $table->float('balance')->default(0);
            $table->string('balance_type', 10)->default('debit');
            $table->timestamps();
            $table->foreign('account_origin_id')->references('id')->on('account_origins')->onDelete('cascade');
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
        Schema::dropIfExists('acc_accounts');
    }
};
