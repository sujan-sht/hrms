<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLabourPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('labour_payments', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id')->nullable();
            $table->double('payable_amount')->nullable();
            $table->double('paid_amount')->nullable();
            $table->integer('nep_year')->nullable();
            $table->integer('eng_year')->nullable();
            $table->integer('nep_month')->nullable();
            $table->integer('eng_month')->nullable();
            $table->date('paid_date')->nullable();
            $table->text('remarks')->nullable();

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
        Schema::dropIfExists('labour_payments');
    }
}
