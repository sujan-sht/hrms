<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppraisalCompetencyResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appraisal_competency_responses', function (Blueprint $table) {
            $table->id();
            $table->integer('appraisal_id')->nullable();
            $table->integer('respondent_id')->nullable();
            $table->integer('competency_id')->nullable();
            $table->string('score')->nullable();
            $table->text('comment')->nullable();
            $table->integer('created_by')->nullable()->comment('employee_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('appraisal_competency_responses');
    }
}
