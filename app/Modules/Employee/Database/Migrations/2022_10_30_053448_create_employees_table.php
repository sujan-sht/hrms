<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            $table->string('employee_id')->nullable();
            $table->string('employee_code')->nullable();
            $table->text('first_name');
            $table->string('middle_name')->nullable();
            $table->text('last_name');
            $table->string('dayoff')->default('Saturday');

            $table->integer('blood_group')->nullable();

            $table->text('profile_pic')->nullable();
            $table->text('citizen_pic')->nullable();
            $table->text('document_pic')->nullable();

            $table->text('phone')->nullable();
            $table->string('mobile')->nullable();
            $table->text('personal_email')->nullable();
            $table->text('official_email')->nullable();

            $table->integer('designation_id')->nullable();
            $table->integer('department_id')->nullable();
            $table->date('join_date')->nullable();
            $table->string('nepali_join_date')->nullable();
            $table->string('job_title')->nullable();

            $table->integer('gender')->nullable();
            $table->integer('marital_status')->nullable();
            $table->text('dob')->nullable();

            $table->boolean('status')->default(1);
            $table->boolean('is_user_access')->default(0);
            $table->boolean('is_parent_link')->default(0);

            $table->integer('temporaryprovince')->nullable();
            $table->integer('temporarydistrict')->nullable();
            $table->text('temporarymunicipality_vdc')->nullable();
            $table->text('temporaryaddress')->nullable();

            $table->integer('permanentprovince')->nullable();
            $table->integer('permanentdistrict')->nullable();
            $table->text('permanentmunicipality_vdc')->nullable();
            $table->text('permanentaddress')->nullable();

            $table->mediumText('archive_reason')->nullable();
            $table->date('archived_date')->nullable();

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
        Schema::dropIfExists('employees');
    }
}

