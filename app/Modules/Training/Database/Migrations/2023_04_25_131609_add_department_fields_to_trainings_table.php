<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDepartmentFieldsToTrainingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trainings', function (Blueprint $table) {
            $table->string('targeted_participant')->after('description')->nullable();
            $table->integer('department_id')->after('targeted_participant')->nullable();
            $table->integer('frequency')->after('department_id')->nullable();
            $table->integer('pax_training')->after('frequency')->nullable();

        });
    }

    /**
     * Reverse the migrations.
    *
     * @return void
     */
    public function down()
    {
        Schema::table('trainings', function (Blueprint $table) {
            $table->dropColumn('targeted_participant');
            $table->dropColumn('department_id');
            $table->dropColumn('frequency');
            $table->dropColumn('pax_training');
        });
    }
}
