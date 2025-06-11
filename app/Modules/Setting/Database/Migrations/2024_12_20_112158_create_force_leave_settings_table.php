<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateForceLeaveSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('force_leave_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('days_limit_from_doj')->nullable();
            $table->tinyInteger('include_holiday')->nullable();
            $table->tinyInteger('include_dayoff')->nullable();
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
        Schema::dropIfExists('force_leave_settings');
    }
}
