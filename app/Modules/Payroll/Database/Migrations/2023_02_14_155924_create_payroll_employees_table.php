<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePayrollEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payroll_employees', function (Blueprint $table) {
            $table->id();
            $table->integer('payroll_id');
            $table->integer('employee_id');
            $table->integer('total_days')->nullable();
            $table->integer('unpaid_days')->nullable();
            $table->integer('total_days_for_payment')->nullable();
            $table->integer('total_income')->nullable();
            $table->integer('total_deduction')->nullable();
            $table->integer('net_salary')->nullable();
            $table->string('remarks')->nullable();
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
        Schema::dropIfExists('payroll_employees');
    }
}
