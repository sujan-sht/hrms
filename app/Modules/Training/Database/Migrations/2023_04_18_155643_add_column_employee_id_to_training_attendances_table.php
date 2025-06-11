<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnEmployeeIdToTrainingAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('training_attendances', function (Blueprint $table) {
            $table->dropColumn('participant_name');
            $table->integer('employee_id')->nullable()->after('feedback');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('training_attendances', function (Blueprint $table) {
            $table->dropColumn('employee_id');
        });
    }
}
