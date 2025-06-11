<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAdjustmentAdvanceAndPayableSalaryToPayrollEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_employees', function (Blueprint $table) {
            $table->float('adjustment')->default(0)->after('net_salary');
            $table->float('advance_amount')->default(0)->after('adjustment');
            $table->double('payable_salary',16,2)->default(0)->after('advance_amount');
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
            $table->dropColumn('adjustment');
            $table->dropColumn('advance_amount');
            $table->dropColumn('payable_salary');
        });
    }
}
