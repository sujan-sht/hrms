<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddProbdateToEmployeePayrollRelatedDetailsTable extends Migration
{
    public function up()
    {
        Schema::table('employee_payroll_related_details', function (Blueprint $table) {
            $table->date('probation_start_date')->nullable()->before('probation_end_date');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_payroll_related_details', function (Blueprint $table) {
            $table->dropColumn('probation_start_date');
        });
    }
}
