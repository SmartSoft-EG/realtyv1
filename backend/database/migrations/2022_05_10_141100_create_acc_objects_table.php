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
        Schema::create('acc_objects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('type', 20)->default('customer');
            $table->string('email')->nullable();
            $table->string('telephone')->nullable();
            $table->string('address')->nullable();
            $table->text('data')->nullable();
            $table->float('balance')->default(0);
            $table->string('balance_type', 20)->default('debit');
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
        Schema::dropIfExists('acc_objects');
    }
};
