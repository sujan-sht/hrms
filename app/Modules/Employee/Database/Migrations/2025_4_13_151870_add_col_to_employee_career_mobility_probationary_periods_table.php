<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColToEmployeeCareerMobilityProbationaryPeriodsTable extends Migration
{
    public function up()
    {
        Schema::table('employee_career_mobility_probationary_periods', function (Blueprint $table) {
            $table->date('extension_from_date')->nullable();
            $table->string('attachment')->nullable();
            $table->text('remarks')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employee_career_mobility_probationary_periods', function (Blueprint $table) {
            $table->dropColumn('extension_from_date');
            $table->dropColumn('attachment');
            $table->dropColumn('remarks');
        });
    }
}
