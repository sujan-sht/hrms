<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddYearlyTaxableSalaryToPayrollEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_employees', function (Blueprint $table) {
            $table->integer('yearly_taxable_salary')->nullable()->after('monthly_total_deduction');
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
            $table->dropColumn('yearly_taxable_salary');
        }); 
    }
}
