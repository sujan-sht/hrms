<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCheckinStartTimeToShiftDayWisesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shift_day_wises', function (Blueprint $table) {
            $table->time('checkin_start_time')->nullable()->after('day');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shift_day_wises', function (Blueprint $table) {
            $table->dropColumn('checkin_start_time');
        });
    }
}
