<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddContractDateFieldsToEmployeePayrollRelatedDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_payroll_related_details', function (Blueprint $table) {
            $table->date('contract_start_date')->nullable()->after('contract_type');
            $table->date('contract_end_date')->nullable()->after('contract_start_date');

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
            $table->dropColumn('contract_start_date');
            $table->dropColumn('contract_end_date');

        });
    }
}
