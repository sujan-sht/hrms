<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeLeaveOpeningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_leave_openings', function (Blueprint $table) {
            $table->id();
            $table->integer('fiscal_year_id')->nullable();
            $table->integer('organization_id')->nullable();
            $table->integer('employee_id')->nullable();
            $table->integer('leave_type_id')->nullable();
            $table->integer('opening_leave')->nullable();
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
        Schema::dropIfExists('employee_leave_openings');
    }
}
