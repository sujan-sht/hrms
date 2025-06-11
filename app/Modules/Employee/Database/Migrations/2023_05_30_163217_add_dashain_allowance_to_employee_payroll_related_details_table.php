<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDashainAllowanceToEmployeePayrollRelatedDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_payroll_related_details', function (Blueprint $table) {
            $table->tinyInteger('dashain_allowance')->nullable()->after('lunch_allowance');
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
            $table->dropColumn('dashain_allowance');
        });
    }
}
