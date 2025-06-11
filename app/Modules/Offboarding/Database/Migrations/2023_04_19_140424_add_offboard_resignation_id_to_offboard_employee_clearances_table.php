<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddOffboardResignationIdToOffboardEmployeeClearancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('offboard_employee_clearances', function (Blueprint $table) {
            $table->integer('offboard_resignation_id')->nullable()->after('offboard_clearance_responsible_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('offboard_employee_clearances', function (Blueprint $table) {
            $table->dropColumn('offboard_resignation_id');

        });
    }
}
