<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvancePaymentLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advance_payment_ledgers', function (Blueprint $table) {
            $table->id();
            $table->integer('advance_id');
            $table->date('date')->nullable();
            $table->text('description')->nullable();
            $table->float('debit')->nullable();
            $table->float('credit')->nullable();
            $table->float('balance')->nullable();
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
        Schema::dropIfExists('advance_payment_ledgers');
    }
}
