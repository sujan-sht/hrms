<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddScoreToAppraisalDevelopmentPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appraisal_development_plans', function (Blueprint $table) {
            $table->string('score')->nullable()->after('reviewer_comment');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appraisal_development_plans', function (Blueprint $table) {
            $table->dropColumn('score');
        });
    }
}
