<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOrgIdColumnToLabourAttendanceMonthlies extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('labour_attendance_monthlies', function (Blueprint $table) {
            $table->integer('org_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('labour_attendance_monthlies', function (Blueprint $table) {
            $table->dropColumn('org_id');
        });
    }
}
