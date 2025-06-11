<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSurveyParticipantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('survey_participants', function (Blueprint $table) {
            $table->id();
            $table->integer('survey_id')->nullable();
            $table->integer('organization_id')->nullable();
            $table->integer('department_id')->nullable();
            $table->integer('level_id')->nullable();
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
        Schema::dropIfExists('survey_participants');
    }
}
