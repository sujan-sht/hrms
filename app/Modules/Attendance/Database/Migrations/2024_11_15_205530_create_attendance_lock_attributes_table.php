<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendanceLockAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendance_lock_attributes', function (Blueprint $table) {
            $table->id();
            $table->integer('attendance_organization_lock_id')->default(0)->nullable();
            $table->integer('attendance_summary_verification_id')->default(0)->nullable();
            $table->integer('emp_id')->default(0)->nullable();
            $table->string('type');
            $table->string('value')->default(1);
            $table->string('item_value');
            $table->string('status')->default(0);
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
        Schema::dropIfExists('attendance_lock_attributes');
    }
}
