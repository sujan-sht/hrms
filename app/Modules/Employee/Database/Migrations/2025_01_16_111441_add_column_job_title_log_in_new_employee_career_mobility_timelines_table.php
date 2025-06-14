<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnJobTitleLogInNewEmployeeCareerMobilityTimelinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('new_employee_career_mobility_timelines', function (Blueprint $table) {
            $table->text('job_title_log')->nullable()->after('remarks');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('new_employee_career_mobility_timelines', function (Blueprint $table) {
            $table->dropColumn('job_title_log');
        });
    }
}
