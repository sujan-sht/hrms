<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnUnpaidLeaveTypeToLeaveDeductionSetupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leave_deduction_setups', function (Blueprint $table) {
            $table->integer('unpaid_leave_type')->nullable()->after('leave_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leave_deduction_setups', function (Blueprint $table) {
            $table->dropColumn('unpaid_leave_type');
        });
    }
}
