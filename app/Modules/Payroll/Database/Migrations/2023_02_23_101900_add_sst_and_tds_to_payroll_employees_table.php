<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSstAndTdsToPayrollEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_employees', function (Blueprint $table) {
            $table->float('sst')->nullable()->after('total_deduction');
            $table->float('tds')->nullable()->after('sst');
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
            $table->dropColumn('sst');
            $table->dropColumn('tds');
        });
    }
}
