<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAppraisalDevelopmentPlansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appraisal_development_plans', function (Blueprint $table) {
            $table->id();
            $table->text('strength')->nullable();
            $table->text('development')->nullable();
            $table->text('support')->nullable();
            $table->text('reviewer_comment')->nullable();
            $table->integer('appraisal_id')->nullable();
            $table->integer('appraisee')->nullable();
            $table->integer('questionere_id')->nullable();
            $table->integer('created_by')->nullable();
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
        Schema::dropIfExists('appraisal_development_plan');
    }
}
