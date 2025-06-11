<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMedicalDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('medical_details', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id')->nullable();
            $table->text('medical_problem')->nullable();
            $table->text('details')->nullable();
            $table->string('insurance_company_name')->nullable();
            $table->text('medical_insurance_details')->nullable();
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
        Schema::dropIfExists('medical_details');
    }
}
