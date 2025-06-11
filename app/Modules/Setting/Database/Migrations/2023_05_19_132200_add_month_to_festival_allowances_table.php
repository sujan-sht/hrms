<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMonthToFestivalAllowancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('festival_allowances', function (Blueprint $table) {
            $table->string('month')->nullable()->after('eligible_month');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('festival_allowances', function (Blueprint $table) {
            $table->dropColumn('month');
        });
    }
}
