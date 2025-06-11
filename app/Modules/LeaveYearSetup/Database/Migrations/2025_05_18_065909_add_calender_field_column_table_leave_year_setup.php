<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCalenderFieldColumnTableLeaveYearSetup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('leave_year_setups', function (Blueprint $table) {
            $table->string('calender_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('leave_year_setups', function (Blueprint $table) {
            $table->dropColumn('calender_type');
        });
    }
}
