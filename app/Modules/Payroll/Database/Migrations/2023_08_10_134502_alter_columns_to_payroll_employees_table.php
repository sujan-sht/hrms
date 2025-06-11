<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterColumnsToPayrollEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_employees', function (Blueprint $table) {
            $table->float('total_income')->nullable()->change();
            $table->float('total_deduction')->nullable()->change();
            $table->float('net_salary')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('payroll_employees', function (Blueprint $table) {
            $table->integer('total_income')->nullable()->change();
            $table->integer('total_deduction')->nullable()->change();
            $table->integer('net_salary')->nullable()->change();
        });
    }
}
