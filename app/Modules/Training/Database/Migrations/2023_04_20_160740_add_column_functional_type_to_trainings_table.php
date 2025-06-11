<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnFunctionalTypeToTrainingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trainings', function (Blueprint $table) {
            $table->string('functional_type')->nullable()->after('type');
            $table->string('training_for')->nullable()->after('no_of_employee');
            $table->double('full_marks', 5, 2)->nullable()->after('training_for');
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
            $table->dropColumn('functional_type');
            $table->dropColumn('training_for');
            $table->dropColumn('full_marks');
        });
    }
}
