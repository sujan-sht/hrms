<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeCareerMobilityDemotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_career_mobility_demotions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->string('contract_type')->nullable();
            $table->string('letter_issue_date')->nullable();
            $table->string('demotion_date')->nullable();
            $table->string('demotion_to')->nullable();
            $table->string('location')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
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
        Schema::dropIfExists('employee_career_mobility_demotions');
    }
}
