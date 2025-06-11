<?php

use App\Modules\Dropdown\Entities\Dropdown;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAccountNoToEmployeePayrollRelatedDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_payroll_related_details', function (Blueprint $table) {
            $table->string('account_no')->nullable();
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
            $table->dropColumn('account_no');
        });
    }
}
