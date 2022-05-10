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
        Schema::create('acc_trans_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trans_id');
            $table->foreign('acc_transaction_id')->references('id')->on('acc_transactions')->onDelete('cascade');
            $table->unsignedBigInteger('account_id');
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->float('depit');
            $table->float('credit');
            $table->longText('description');
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
        Schema::dropIfExists('acc_trans_details');
    }
};
