<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDivisionAttendanceMonthliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('division_attendance_monthlies', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id')->nullable();
            $table->date('date')->nullable();
            $table->string('nepali_date')->nullable();
            $table->tinyInteger('is_present')->nullable();
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
        Schema::dropIfExists('division_attendance_monthlies');
    }
}
