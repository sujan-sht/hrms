<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraWorkingDaysToPayrollEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('payroll_employees', function (Blueprint $table) {
            $table->string('total_working_days')->nullable()->after('total_days');
            $table->string('extra_working_days')->nullable()->after('total_working_days');
            $table->string('extra_working_days_amount')->nullable()->after('tds');
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
            $table->dropColumn('total_working_days');
            $table->dropColumn('extra_working_days');
            $table->dropColumn('extra_working_days_amount');
        });
    }
}
