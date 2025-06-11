<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeCareerMobilityConfirmationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_career_mobility_confirmations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->string('letter_issue_date')->nullable();
            $table->string('confirmation_date')->nullable();
            $table->string('contract_type')->nullable(); // should be changed into permanent
            $table->string('contract_start_date')->nullable();
            $table->string('contract_end_date')->nullable();
            $table->unsignedBigInteger('designation_id')->nullable(); // changed
            $table->unsignedBigInteger('branch_id')->nullable(); // no change
            $table->unsignedBigInteger('department_id')->nullable(); // no change
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
        Schema::dropIfExists('employee_career_mobility_confirmations');
    }
}
