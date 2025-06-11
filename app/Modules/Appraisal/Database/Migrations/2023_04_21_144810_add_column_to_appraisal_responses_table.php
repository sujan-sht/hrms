<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToAppraisalResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('appraisal_responses', function (Blueprint $table) {
            $table->integer('appraisal_id')->nullable()->after('id');
            $table->string('comment')->nullable()->after('score');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('appraisal_responses', function (Blueprint $table) {
            $table->dropColumn('appraisal_id');
            $table->dropColumn('comment');
        });
    }
}
