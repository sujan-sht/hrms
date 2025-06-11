<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendanceSummaryVerificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_summary_verifications', function (Blueprint $table) {
            $table->id();
            $table->integer('attendance_organization_lock_id')->default(0)->nullable();
            $table->integer('employee_id')->default(0)->nullable();
            $table->integer('organization_id')->default(0)->nullable();
            $table->string('calender_type');
            $table->string('total_days');
            $table->string('working_days');
            $table->string('dayoffs');
            $table->string('public_holiday');
            $table->string('working_hour');
            $table->string('worked_days');
            $table->string('worked_hour');
            $table->string('unworked_hour');
            $table->string('leave_taken');
            $table->string('paid_leave_taken');
            $table->string('unpaid_leave_taken');
            $table->string('absent_days');
            $table->string('over_stay');
            $table->string('ot_value');
            $table->string('lock_type')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attendance_summary_verifications');
    }
}
