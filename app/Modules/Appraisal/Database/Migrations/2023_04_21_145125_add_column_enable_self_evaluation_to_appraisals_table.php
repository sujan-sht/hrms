<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnEnableSelfEvaluationToAppraisalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appraisals', function (Blueprint $table) {
            $table->tinyInteger('enable_self_evaluation')->nullable()->after('type');
            $table->tinyInteger('self_evaluation_type')->nullable()->after('enable_self_evaluation');
            $table->tinyInteger('enable_supervisor_evaluation')->nullable()->after('self_evaluation_type');
            $table->tinyInteger('supervisor_evaluation_type')->nullable()->after('enable_supervisor_evaluation');
            $table->tinyInteger('enable_hod_evaluation')->nullable()->after('supervisor_evaluation_type');
            $table->tinyInteger('hod_evaluation_type')->nullable()->after('enable_hod_evaluation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appraisals', function (Blueprint $table) {
            $table->dropColumn('enable_self_evaluation');
            $table->dropColumn('self_evaluation_type');
            $table->dropColumn('enable_supervisor_evaluation');
            $table->dropColumn('supervisor_evaluation_type');
            $table->dropColumn('enable_hod_evaluation');
            $table->dropColumn('hod_evaluation_type');
        });
    }
}
