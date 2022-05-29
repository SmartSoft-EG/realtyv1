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
        Schema::create('realty_units', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('realty_id');
            $table->string('code');
            $table->string('type')->default('apartment');
            $table->string('name');
            $table->tinyInteger('floor')->default(0);
            $table->integer('size');
            $table->string('description');
            $table->string('state')->default('free');
            $table->float('price')->nullable();
            $table->timestamps();
            $table->foreign('realty_id')->references('id')->on('realty');
        });
        // DB::unprepared('CREATE TRIGGER add_activity AFTER INSERT ON `items` FOR EACH ROW
        // -- BEGIN
        // --     DECLARE x VARCHAR(50);
        // --     DECLARE y VARCHAR(50);
        // -- SET x = CONCAT('trigger test: ', NEW.id);
        // -- SET @INC = (SELECT name FROM users WHERE id=1 LIMIT 1);
        // -- INSERT INTO `activities` (`user_id`,`name`,`state`,`activable_type`,`activable_id`) VALUES (1,  @inc, 'active', 'App/\Models/\RealtyUnit', NEW.id);
        // END');
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
