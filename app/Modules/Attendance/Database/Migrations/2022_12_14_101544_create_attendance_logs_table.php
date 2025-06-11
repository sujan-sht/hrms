<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendanceLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->integer('org_id')->default(0)->nullable();
            $table->integer('biometric_emp_id')->default(0)->nullable();
            $table->integer('emp_id')->default(0)->nullable();
            $table->integer('inout_mode')->default(0)->nullable('1-checkin;2-checkout');
            $table->integer('verifymode')->default(0)->comment('1-fingerprint; 2-card; 3-face')->nullable();
            $table->time('time')->nullable();
            $table->string('punch_from')->default('biometric')->comment('web; biometric')->nullable();
            $table->string('location')->nullable();
            $table->decimal('lat', 11, 8)->nullable();
            $table->decimal('long', 11, 8)->nullable();
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
        Schema::dropIfExists('attendance_logs');
    }
}
