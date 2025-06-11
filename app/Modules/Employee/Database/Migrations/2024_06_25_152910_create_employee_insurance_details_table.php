<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeInsuranceDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_insurance_details', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id')->nullable();
            $table->tinyInteger('gpa_enable')->nullable();
            $table->double('gpa_sum_assured', 14,2)->nullable();
            $table->double('medical_coverage', 14,2)->nullable();
            $table->double('individual', 14,2)->nullable();
            $table->double('spouse', 14,2)->nullable();
            $table->double('kid_one', 14,2)->nullable();
            $table->double('kid_two', 14,2)->nullable();
            $table->double('mom', 14,2)->nullable();
            $table->double('dad', 14,2)->nullable();
            $table->tinyInteger('gmi_enable')->nullable();
            $table->double('gmi_sum_assured', 14,2)->nullable();
            $table->double('hospitality_in_perc', 8,2)->nullable();
            $table->double('hospitality_in_amt', 14,2)->nullable();
            $table->double('domesticality_in_perc', 8,2)->nullable();
            $table->double('domesticality_in_amt', 14,2)->nullable();
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
        Schema::dropIfExists('employee_insurance_details');
    }
}
