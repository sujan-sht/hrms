<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeCareerMobilityTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_career_mobility_transfers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id');
            $table->string('letter_issue_date')->nullable();
            $table->string('transfer_date')->nullable();
            $table->string('job_title')->nullable();
            $table->string('effective_date')->nullable();
            $table->unsignedBigInteger('branch_transfer_id')->nullable();
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
        Schema::dropIfExists('employee_career_mobility_transfers');
    }
}
