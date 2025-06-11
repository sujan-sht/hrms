<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeePayrollRelatedDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_payroll_related_details', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id')->nullable();
            $table->string('join_date')->nullable();
            $table->string('basic_salary')->nullable();
            $table->string('dearness_allowance')->nullable();
            $table->string('lunch_allowance')->nullable();
            $table->integer('contract_type')->nullable();
            $table->tinyInteger('probation_status')->nullable();
            $table->integer('probation_period_days')->nullable();
            $table->string('probation_end_date')->nullable();
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('employee_payroll_related_details');
    }
}
