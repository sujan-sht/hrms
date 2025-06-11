<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeCareerMobilityAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_career_mobility_appointments', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('employee_id')->nullable();
            $table->string('letter_issue_date')->nullable();
            $table->string('appointment_date')->nullable();
            $table->string('effective_date')->nullable();
            $table->string('contract_type')->nullable(); // contract or probation or regular

            // it is only for probation and contract
            $table->string('from_date')->nullable();
            $table->string('to_date')->nullable();

            $table->unsignedBigInteger('designation_id')->nullable();
            $table->unsignedBigInteger('branch_id')->nullable();
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
        Schema::dropIfExists('employee_career_mobility_appointments');
    }
}
