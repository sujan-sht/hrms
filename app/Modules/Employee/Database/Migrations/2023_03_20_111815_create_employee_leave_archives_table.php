<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeLeaveArchivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_leave_archives', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id');
            $table->integer('organization_id')->nullable();
            $table->integer('fiscal_year_id')->nullable();
            $table->integer('leave_type_id')->nullable();
            $table->integer('opening_leave')->nullable();
            $table->integer('leave_remaining')->nullable();
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
        Schema::dropIfExists('employee_leave_archives');
    }
}
