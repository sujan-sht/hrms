<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreviousJobDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('previous_job_details', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id')->nullable();
            $table->string('company_name')->nullable();
            $table->string('address')->nullable();
            $table->date('from_date')->nullable();
            $table->date('to_date')->nullable();
            $table->string('job_title')->nullable();
            $table->string('designation_on_joining')->nullable();
            $table->string('designation_on_leaving')->nullable();
            $table->string('industry_type')->nullable();
            $table->string('break_in_career')->nullable();
            $table->text('reason_for_leaving')->nullable();
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
        Schema::dropIfExists('previous_job_details');
    }
}
