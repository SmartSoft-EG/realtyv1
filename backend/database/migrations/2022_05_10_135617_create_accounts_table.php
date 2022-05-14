<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
            $table->id();
            $table->unsignedBigInteger('acc_num');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('accounts')->onDelete('cascade');
            $table->nullableMorphs('accountable');
            $table->string('name')->unique();
            $table->string('type', 20)->default('main');
            $table->smallInteger('level')->default(1);
            $table->string('status', 10)->default('active');
            $table->boolean('is_main')->default(0);
            $table->float('debit')->default(0);
            $table->float('credit')->default(0);
            $table->timestamps();
        });
        //add root element for accounts
        DB::table('accounts')->insert(['acc_num' => 0, 'name' => 'root', 'type' => 'root',  'is_main' => 1]);
        DB::table('accounts')->update(['id' => 0, 'parent_id' => 0]);
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
