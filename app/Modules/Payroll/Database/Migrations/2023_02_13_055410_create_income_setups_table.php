<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIncomeSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('income_setups', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('short_name');
            $table->text('description')->nullable();
            $table->integer('method')->nullable()->comment('1 => fixed, 2 => percentage');
            $table->float('amount', 10, 2)->nullable();
            $table->float('percentage', 10, 2)->nullable();
            $table->integer('salary_type')->nullable()->comment('1 => basic, 2 => gross');
            $table->integer('taxable_status')->nullable();
            $table->integer('leave_deduction')->nullable();
            $table->integer('pf_calculation')->nullable();
            $table->integer('order')->nullable();
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('income_setups');
    }
}
