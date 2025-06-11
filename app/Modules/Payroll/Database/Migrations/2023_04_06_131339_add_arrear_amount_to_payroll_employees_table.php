<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddArrearAmountToPayrollEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_employees', function (Blueprint $table) {
            $table->float('arrear_amount')->nullable()->after('total_income');
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
            $table->dropColumn('arrear_amount');
        });
    }
}
