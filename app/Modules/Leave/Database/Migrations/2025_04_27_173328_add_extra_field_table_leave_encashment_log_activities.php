<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddExtraFieldTableLeaveEncashmentLogActivities extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leave_encashment_log_activities', function (Blueprint $table) {
            $table->integer('payroll_id')->nullable();
            $table->integer('employee_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leave_encashment_log_activities', function (Blueprint $table) {
            $table->dropColumn('payroll_id');
            $table->dropColumn('employee_id');
        });
    }
}
