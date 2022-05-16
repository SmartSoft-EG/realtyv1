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
        Schema::create('realty_owners', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->morphs('ownerable');
            $table->string('telephone')->nullable();
            $table->string('address')->nullable();
            $table->string('national_id')->nullable();
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
        Schema::dropIfExists('realty_owners');
    }
};
