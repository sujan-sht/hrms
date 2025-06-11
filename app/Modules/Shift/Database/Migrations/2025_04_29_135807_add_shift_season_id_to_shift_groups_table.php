<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddShiftSeasonIdToShiftGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shift_groups', function (Blueprint $table) {
            $table->unsignedBigInteger('shift_season_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shift_groups', function (Blueprint $table) {
            $table->dropColumn('shift_season_id');
        });
    }
}
