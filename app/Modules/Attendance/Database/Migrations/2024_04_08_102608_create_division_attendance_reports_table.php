<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDivisionAttendanceReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('division_attendance_reports', function (Blueprint $table) {
            $table->id();
            $table->integer('employee_id')->nullable();
            $table->date('date')->nullable();
            $table->text('nepali_date')->nullable();
            $table->integer('is_absent')->nullable();
            $table->time('checkin')->nullable();
            $table->time('checkout')->nullable();
            $table->double('actual_hr', 5,2)->nullable();
            $table->double('worked_hr', 5,2)->nullable();
            $table->double('ot_hr', 5,2)->nullable();
            $table->integer('status')->nullable();
            $table->text('remarks')->nullable();
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
        Schema::dropIfExists('division_attendance_reports');
    }
}
