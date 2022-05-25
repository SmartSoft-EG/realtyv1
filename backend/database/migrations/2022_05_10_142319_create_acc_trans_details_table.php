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
        Schema::create('acc_trans_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('acc_transaction_id');
            $table->unsignedBigInteger('account_id');
            $table->float('debit')->default(0);
            $table->float('credit')->default(0);
            $table->float('balance')->default(0);
            $table->string('balance_type', 20)->default('debit');
            $table->nullableMorphs('detailable');
            $table->float('related_balance')->default(0);
            $table->string('related_balance_type', 20)->default('debit');
            $table->timestamps();
            $table->foreign('account_id')->references('id')->on('accounts');
            $table->foreign('acc_transaction_id')->references('id')->on('acc_transactions')->onDelete('cascade');
        });


        // DB::unprepared('CREATE TRIGGER update_balance AFTER INSERT ON `items` FOR EACH ROW
        // BEGIN
        // UPDATE accounts a
        //     SET a.debit = a.debit + NEW.debit ,a.credit = a.credit + NEW.credit
        //     WHERE a.id = NEW.account_id;
        // END');
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
