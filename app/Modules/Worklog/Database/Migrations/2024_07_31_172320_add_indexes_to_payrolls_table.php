<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIndexesToPayrollsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->string('nepali_date', 191)->change();
            $table->index(['emp_id', 'nepali_date']);
        });
        Schema::table('leaves', function (Blueprint $table) {
            $table->string('nepali_date', 191)->change();
            $table->index(['employee_id','nepali_date','status']);
        });
        Schema::table('income_setups', function (Blueprint $table) {
            $table->index('id');
            $table->index(['short_name', 'organization_id']);
        });
        Schema::table('deduction_setups', function (Blueprint $table) {
            $table->index('id');
            $table->index(['short_name', 'organization_id']);
        });
        Schema::table('employee_setups', function (Blueprint $table) {
            $table->index(['employee_id', 'reference','reference_id']);
        });
        Schema::table('payroll_employees', function (Blueprint $table) {
            $table->index('id');
        });
        Schema::table('payroll_employees', function (Blueprint $table) {
            $table->index('payroll_id','employee_id');
        });
        Schema::table('payroll_incomes', function (Blueprint $table) {
            $table->index(['payroll_id', 'payroll_employee_id']);
            $table->index('income_setup_id');
        });
        Schema::table('payroll_deductions', function (Blueprint $table) {
            $table->index(['payroll_id', 'payroll_employee_id']);
            $table->index('deduction_setup_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropIndex(['emp_id','nepali_date']);
        });
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropIndex(['employee_id','nepali_date','status']);
        });
        Schema::table('income_setups', function (Blueprint $table) {
            $table->dropIndex('income_setups_id_index');
            $table->dropIndex(['short_name', 'organization_id']);
        });
        Schema::table('deduction_setups', function (Blueprint $table) {
            $table->dropIndex('deduction_setups_id_index');
            $table->dropIndex(['short_name', 'organization_id']);
        });
        Schema::table('employee_setups', function (Blueprint $table) {
            $table->dropIndex(['employee_id', 'reference','reference_id']);
        });
        Schema::table('payroll_employees', function (Blueprint $table) {
            $table->dropIndex('id');
        });
        Schema::table('payroll_employees', function (Blueprint $table) {
            $table->dropIndex('payroll_id','employee_id');
        });
        Schema::table('payroll_incomes', function (Blueprint $table) {
            $table->dropIndex(['payroll_id', 'payroll_employee_id']);
            $table->dropIndex('income_setup_id');
        });
        Schema::table('payroll_deductions', function (Blueprint $table) {
            $table->dropIndex(['payroll_id', 'payroll_employee_id']);
            $table->dropIndex('deduction_setup_id');
        });
    }
}
