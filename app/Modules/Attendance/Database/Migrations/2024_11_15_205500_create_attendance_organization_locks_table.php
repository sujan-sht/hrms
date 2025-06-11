<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendanceOrganizationLocksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_organization_locks', function (Blueprint $table) {
            $table->id();
            $table->integer('organization_id')->default(0)->nullable();
            $table->string('calender_type');
            $table->string('year');
            $table->string('month');
            $table->string('created_np_datetime');
            $table->string('created_eng_datetime');
            $table->string('lock_type')->default(0);
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
        Schema::dropIfExists('attendance_organization_locks');
    }
}
