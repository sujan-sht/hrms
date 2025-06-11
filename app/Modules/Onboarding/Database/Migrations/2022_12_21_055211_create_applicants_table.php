<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApplicantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->integer('manpower_requisition_form_id');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('address')->nullable();
            $table->string('city')->nullable();
            $table->string('province')->nullable();
            $table->string('mobile')->nullable();
            $table->string('email')->nullable();
            $table->integer('gender')->nullable();
            $table->integer('source')->nullable();
            $table->integer('experience')->nullable();
            $table->integer('expected_salary')->nullable();
            $table->text('skills')->nullable();
            $table->string('resume')->nullable();
            $table->string('cover_letter')->nullable();
            $table->tinyInteger('status')->nullable();
            $table->integer('latest_stage')->nullable();
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
        Schema::dropIfExists('applicants');
    }
}
