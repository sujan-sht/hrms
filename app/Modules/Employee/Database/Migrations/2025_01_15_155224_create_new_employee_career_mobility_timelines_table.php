<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNewEmployeeCareerMobilityTimelinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('new_employee_career_mobility_timelines', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employee_id')->nullable();
            $table->string('event_type')->nullable();
            $table->string('event_date')->nullable();
            $table->text('description')->nullable();
            $table->string('remarks')->nullable();
            $table->text('contract_type_log')->nullable();
            $table->text('designation_log')->nullable();
            $table->text('branch_log')->nullable();
            $table->text('department_log')->nullable();
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
        Schema::dropIfExists('new_employee_career_mobility_timelines');
    }
}
