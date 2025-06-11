<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsMultiDayShiftColumnToShiftSeasonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shift_seasons', function (Blueprint $table) {
            $table->tinyInteger('is_multi_day_shift')->default(0);

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shift_seasons', function (Blueprint $table) {
            $table->dropColumn('is_multi_day_shift');
        });
    }
}
