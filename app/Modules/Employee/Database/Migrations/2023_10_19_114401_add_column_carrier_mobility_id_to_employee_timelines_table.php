<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnCarrierMobilityIdToEmployeeTimelinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employee_timelines', function (Blueprint $table) {
            $table->integer('carrier_mobility_id')->nullable()->after('reference_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_timelines', function (Blueprint $table) {
            $table->dropColumn('carrier_mobility_id');
        });
    }
}
